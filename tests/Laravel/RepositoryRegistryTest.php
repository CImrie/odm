<?php


namespace CImrie\Odm\Tests\Laravel;


use CImrie\ODM\OdmServiceProvider;
use CImrie\ODM\Repositories\RepositoryResolverRegistry;
use CImrie\ODM\Repositories\ResolverCallbacks;
use CImrie\Odm\Tests\Traits\Odm as OdmTrait;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\UnitOfWork;
use Illuminate\Mail\Mailer;
use Tests\TestCase;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Odm;

class RepositoryRegistryTest extends TestCase
{
    use OdmTrait, ResolverCallbacks;
    /**
     * @test
     */
    public function can_set_custom_repositories()
    {
        $this->addMongoDbToConfig();
        $this->app->make('config')->set('odm.use_custom_repositories', true);

        $this->app->register(OdmServiceProvider::class);

        /** @var RepositoryResolverRegistry $repoRegistry */
        $repoRegistry = $this->app->make(RepositoryResolverRegistry::class);
        $repoRegistry->addResolver(CustomDoc::class, $this->getResolverFor(CustomDocRepo::class));

        /** @var DocumentManager $documentManager */
        $documentManager = $this->app->make(DocumentManager::class);
        $repo = $documentManager->getRepository(CustomDoc::class);

        $this->assertInstanceOf(CustomDocRepo::class, $repo);
    }

    /**
     * @test
     */
    public function can_set_custom_repository_with_service_dependency()
    {
        $this->addMongoDbToConfig();
        $this->app->make('config')->set('odm.use_custom_repositories', true);

        $this->app->register(OdmServiceProvider::class);

        /** @var RepositoryResolverRegistry $repoRegistry */
        $repoRegistry = $this->app->make(RepositoryResolverRegistry::class);
        $resolver = function(DocumentManager $documentManager, UnitOfWork $unitOfWork, ClassMetadata $classMetadata) {
            return new DepRepo($this->app->make(Mailer::class), $documentManager, $unitOfWork, $classMetadata);
        };

        $repoRegistry->addResolver(CustomDoc::class, $resolver);

        /** @var DocumentManager $documentManager */
        $documentManager = $this->app->make(DocumentManager::class);
        $repo = $documentManager->getRepository(CustomDoc::class);

        $this->assertInstanceOf(DepRepo::class, $repo);
        $this->assertInstanceOf(Mailer::class, $repo->getMailer());
    }
}

/**
 * Class CustomDoc
 * @package CImrie\Odm\Tests\Laravel
 * @Odm\Document
 */
class CustomDoc {
    /**
     * @Odm\Id
     */
    protected $id;
}

class CustomDocRepo extends DocumentRepository {
    public function findByTwo()
    {

    }
}

class DepRepo extends DocumentRepository {

    /**
     * @var Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer, DocumentManager $dm, UnitOfWork $uow, ClassMetadata $classMetadata)
    {
        $this->mailer = $mailer;
        parent::__construct($dm, $uow, $classMetadata);
    }

    public function getMailer()
    {
        return $this->mailer;
    }
}