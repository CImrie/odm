<?php


namespace CImrie\Odm\Tests\Unit\Repositories;


use CImrie\ODM\ManagerRegistry;
use CImrie\ODM\Repositories\RepositoryResolverRegistry;
use CImrie\Odm\Tests\Models\TestUser;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Tests\Mapping\TestCustomRepositoryClass;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Mockery as m;

class RepositoryResolverRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RepositoryResolverRegistry
     */
    protected $registry;

    protected function setUp()
    {
        parent::setUp();
        $managerRegistry = m::mock(ManagerRegistry::class);
        $managerRegistry->shouldReceive('getManagerName')->andReturn('foo');

        $this->registry = new RepositoryResolverRegistry($managerRegistry);
    }

    /**
     * @test
     */
    public function can_add_repository_resolver_to_registry()
    {
        $resolver = function (DocumentManager $documentManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) {
            return new TestCustomRepositoryClass($documentManager, $unitOfWork, $classMetadata);
        };

        $document = TestUser::class;

        $this->registry->addResolver($document, $resolver);

        $this->assertEquals($resolver, $this->registry->getResolver($document)->getFactory());
    }

    /**
     * @test
     */
    public function can_resolve_a_repository_for_a_manager()
    {
        $manager = m::mock(DocumentManager::class);
        $unitOfWork = m::mock(UnitOfWork::class);
        $classMetadata = m::mock(ClassMetadata::class);

        $manager->shouldReceive('getUnitOfWork')->andReturn($unitOfWork);
        $manager->shouldReceive('getClassMetadata')->andReturn($classMetadata);

        $resolver = function (DocumentManager $documentManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) {
            return new ResolveableRepo($documentManager, $unitOfWork, $classMetadata);
        };

        $document = TestUser::class;

        $this->registry->addResolver($document, $resolver);
        $repository = $this->registry->getRepository($manager, $document);

        $this->assertNotNull($repository);
        $this->assertInstanceOf(ResolveableRepo::class, $repository);
    }

    /**
     * @test
     */
    public function can_get_default_repository_if_no_resolver_set()
    {
        $document = TestUser::class;

        $manager = m::mock(DocumentManager::class);
        $config = m::mock(Configuration::class);
        $config->shouldReceive('getDefaultRepositoryClassName')->andReturn(DocumentRepository::class);

        $manager->shouldReceive('getConfiguration')->andReturn($config);
        $unitOfWork = m::mock(UnitOfWork::class);
        $classMetadata = new ClassMetadata($document);

        $manager->shouldReceive('getUnitOfWork')->andReturn($unitOfWork);
        $manager->shouldReceive('getClassMetadata')->andReturn($classMetadata);


        $repo = $this->registry->getRepository($manager, $document);
        $this->assertInstanceOf(DocumentRepository::class, $repo);
        $this->assertEquals($document, $repo->getClassName());
    }

    /**
     * @test
     */
    public function resolver_only_called_once_per_manager()
    {
        $manager = m::mock(DocumentManager::class);
        $unitOfWork = m::mock(UnitOfWork::class);
        $classMetadata = m::mock(ClassMetadata::class);

        $callOnce = m::mock(CallOnce::class);
        $callOnce->shouldReceive('yep')->once();

        $manager->shouldReceive('getUnitOfWork')->andReturn($unitOfWork);
        $manager->shouldReceive('getClassMetadata')->andReturn($classMetadata);

        $resolver = function (DocumentManager $documentManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) use($callOnce) {
            $callOnce->yep();
            return new ResolveableRepo($documentManager, $unitOfWork, $classMetadata);
        };

        $document = TestUser::class;

        $this->registry->addResolver($document, $resolver);

        $this->registry->getRepository($manager, $document);
        $this->registry->getRepository($manager, $document);
        $repository = $this->registry->getRepository($manager, $document);

        $this->assertNotNull($repository);
        $this->assertInstanceOf(ResolveableRepo::class, $repository);
    }

    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}

class ResolveableRepo extends DocumentRepository {

}

class CallOnce {
    public function yep()
    {
        return true;
    }
}