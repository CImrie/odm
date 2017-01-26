<?php


namespace Tests\Mapping;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use Tests\Models\Profile;
use Tests\Models\TestUser;
use CImrie\ODM\Mapping\Reference;

class ReferenceBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Reference
     */
    protected $builder;

    /**
     * @var ClassMetadataInfo
     */
    protected $cm;

    public function setUp()
    {
        $this->cm = new ClassMetadataInfo(TestUser::class);
        $this->builder = new Reference();
    }

    public function test_can_reference_one_document()
    {
        $builder = $this->builder->one(
            $property = 'user',
            $entity = TestUser::class
        );

        $this->cm->mapOneReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            ['targetDocument' => TestUser::class],
            $this->cm->fieldMappings['user']
        );
    }

    public function test_can_reference_many_documents()
    {
        $builder = $this->builder->many(
            $property = 'followers',
            $entity = TestUser::class
        );

        $this->cm->mapManyReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            ['targetDocument' => TestUser::class],
            $this->cm->fieldMappings['followers']
        );
    }

    public function test_can_reference_many_mixed_types_of_documents()
    {
        $builder = $this->builder->many(
            $property = 'followers'
        );

        $this->cm->mapManyReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            ['type' => 'many'],
            $this->cm->fieldMappings['followers']
        );
    }

    public function test_can_reference_many_mixed_types_of_documents_with_discriminator_map()
    {
        $map = [
            'test' => TestUser::class
        ];

        $builder = $this->builder->many(
            $property = 'followers'
        )->discriminateUsing($map);

        $this->cm->mapManyReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            [
                'type'             => 'many',
                'discriminatorMap' => $map
            ],
            $this->cm->fieldMappings['followers']
        );
    }

    public function test_can_reference_one()
    {
        // Set up User->Profile Side
        $builder = $this->builder->one(
            $property = 'profile',
            $targetDocument = Profile::class
        )->inversedBy('user');

        $userCm = $this->cm;
        $userCm->mapOneReference($builder->asArray());

        // Set up Profile->User Side
        $builder = $this->builder->one(
            $property = 'user',
            TestUser::class
        )->mappedBy('profile');

        $profileCm = new ClassMetadataInfo(Profile::class);
        $profileCm->mapOneReference($builder->asArray());


        $this->assertArraySubset(
            [
                'inversedBy' => 'user',
                'type'       => 'one'
            ],
            $userCm->fieldMappings['profile']
        );

        $this->assertArraySubset(
            [
                'mappedBy' => 'profile'
            ],
            $profileCm->fieldMappings['user']
        );

        $this->assertFluentSetter($builder);
    }

    public function test_can_reference_many()
    {
        // User -> Profile
        $builder = $this->builder->many(
            $property = 'profile',
            $targetDocument = Profile::class
        )->inversedBy('user');

        $userCm = $this->cm;
        $userCm->mapManyReference($builder->asArray());

        $this->assertArraySubset(
            [
                'inversedBy' => 'user',
                'type'       => 'many',
            ],
            $userCm->fieldMappings['profile']
        );
    }

    public function test_can_customise_X_to_many_with_criteria_filter()
    {
        $builder = $this->builder->many(
            $property = 'profile',
            $targetDocument = Profile::class
        )
            ->inversedBy('user')
            ->criteria(['profile.id' => '1']);

        $userCm = $this->cm;
        $userCm->mapManyReference($builder->asArray());

        $this->assertArraySubset(
            [
                'criteria' => [
                    'profile.id' => '1',
                ]
            ],
            $userCm->fieldMappings['profile']
        );
    }

    public function test_can_customise_X_to_many_with_repository_method_filter()
    {
        $builder = $this->builder->many(
            $property = 'profile',
            $targetDocument = Profile::class
        )
            ->inversedBy('user')
            ->repositoryMethod('filterMe');

        $userCm = $this->cm;
        $userCm->mapManyReference($builder->asArray());

        $this->assertArraySubset(
            [
                'repositoryMethod' => 'filterMe'
            ],
            $userCm->fieldMappings['profile']
        );
    }

    private function assertFluentSetter($builder)
    {
        $this->assertInstanceOf(\CImrie\ODM\Mapping\References\Reference::class, $builder);
    }
}