<?php


namespace Tests\Mapping;


use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
use CImrie\ODM\Mapping\Reference;
use Tests\Unit\Models\TestUser;

class ReferenceBuilderTest extends \PHPUnit_Framework_TestCase {

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
        )->discriminateUsing($map)
        ;

        $this->cm->mapManyReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            [
                'type' => 'many',
                'discriminatorMap' => $map
            ],
            $this->cm->fieldMappings['followers']
        );
	}

	public function test_can_reference_one_to_one()
	{
        $builder = $this->builder->one(
            $property = 'user',
            TestUser::class
        )->mappedBy('user')
            ;

        $this->cm->mapOneReference($builder->asArray());

        $this->assertFluentSetter($builder);
        $this->assertArraySubset(
            [
                'mappedBy' => 'user'
            ],
            $this->cm->fieldMappings['user']
        );

        // todo! - need to test mapping both ways with different entities
	}

	public function test_can_reference_one_to_many()
	{

	}

	public function test_can_reference_many_to_one()
	{

	}

	public function test_can_reference_many_to_many()
	{

	}

	public function test_can_customise_X_to_many_with_criteria_filter()
	{

	}

	public function test_can_customise_X_to_many_with_repository_method_filter()
	{

	}

	private function assertFluentSetter($builder)
	{
		$this->assertInstanceOf(\CImrie\ODM\Mapping\References\Reference::class, $builder);
	}
}