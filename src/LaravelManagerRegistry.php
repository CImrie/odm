<?php


namespace CImrie\ODM;


use CImrie\ODM\Common\Config;
use CImrie\ODM\Common\Registries\DocumentManagerRegistry;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Proxy\Proxy;
use MongoDB\Exception\InvalidArgumentException;


class LaravelManagerRegistry implements DocumentManagerRegistry
{
    /**
     * @var array
     */
    protected $managers = [];

    /**
     * @var DocumentManager[]
     */
    protected $resolvedManagers = [];

    /**
     * @var string
     */
    protected $defaultManagerName = 'default';

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var Connection[]
     */
    protected $resolvedConnections = [];

    /**
     * @var string
     */
    protected $defaultConnectionName = 'default';

    /**
     * @var DocumentManagerFactory
     */
    protected $factory;

    public function __construct(DocumentManagerFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getDefaultManagerName()
    {
        return $this->defaultManagerName;
    }

    public function addManager($name, Config $config)
    {
        $this->managers[$name] = function() use ($config) {
            return $this->factory->create($config);
        };

        $this->connections[$name] = function() use($name) {
            return $this->getManager($name)->getConnection();
        };

        return $this;
    }

    /**
     * @param null $name
     * @return DocumentManager
     */
    public function getManager($name = null)
    {
        $name = $name ?: $this->getDefaultManagerName();

        if (!$this->managerExists($name)) {
            throw new InvalidArgumentException(sprintf('Doctrine Manager named "%s" does not exist.', json_encode($name)));
        }

        if (isset($this->resolvedManagers[$name])) {
            return $this->resolvedManagers[$name];
        }

        return $this->resolveManager($name);
    }

    public function getManagers()
    {
        $managers = [];

        foreach($this->managers as $name => $resolver)
        {
            $managers[$name] = $this->getManager($name);
        }

        return $managers;
    }

    public function resetManager($name = null)
    {
        return $this->resolveManager($name);
    }

    public function getAliasNamespace($alias)
    {
        foreach ($this->getManagerNames() as $name) {
            try {
                return $this->getManager($name)->getConfiguration()->getDocumentNamespace($alias);
            } catch (MongoDBException $e) {
            }
        }

        throw MongoDBException::unknownDocumentNamespace($alias);
    }

    public function getManagerNames()
    {
        return array_keys($this->managers);
    }

    public function getRepository($persistentObject, $persistentManagerName = null)
    {
        return $this->getManager($persistentManagerName)->getRepository($persistentObject);
    }

    public function getManagerForClass($class)
    {
        // Check for namespace alias
        if (strpos($class, ':') !== false) {
            list($namespaceAlias, $simpleClassName) = explode(':', $class, 2);
            $class                                  = $this->getAliasNamespace($namespaceAlias) . '\\' . $simpleClassName;
        }

        $proxyClass = new \ReflectionClass($class);
        if ($proxyClass->implementsInterface(Proxy::class)) {
            $class = $proxyClass->getParentClass()->getName();
        }

        foreach ($this->getManagerNames() as $name) {
            $manager = $this->getManager($name);

            if (!$manager->getMetadataFactory()->isTransient($class)) {
                foreach ($manager->getMetadataFactory()->getAllMetadata() as $metadata) {
                    if ($metadata->getName() === $class) {
                        return $manager;
                    }
                }
            }
        }
    }

    protected function managerExists($name = null)
    {
        return isset($this->managers[$name]);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function resolveManager($name)
    {
        $resolved = $this->managers[$name]();
        $this->resolvedManagers[$name] = $resolved;

        return $resolved;
    }

    public function getDefaultConnectionName()
    {
        return $this->defaultConnectionName;
    }

    public function getConnection($name = null)
    {
        $name = $name ?: $this->getDefaultConnectionName();

        if (!$this->connectionExists($name)) {
            throw new InvalidArgumentException(sprintf('Connection for Manager named "%s" does not exist.', json_encode($name)));
        }

        return $this->connections[$name];
    }

    protected function resolveConnection($name)
    {
        $resolved = $this->connections[$name]();
        $this->resolvedConnections[$name] = $resolved;

        return $resolved;
    }

    public function getConnections()
    {
        return $this->connections;
    }

    public function getConnectionNames()
    {
        return array_keys($this->connections);
    }

    protected function connectionExists($name = null)
    {
        return isset($this->connections[$name]);
    }
}