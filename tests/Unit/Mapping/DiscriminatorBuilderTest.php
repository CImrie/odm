<?php


namespace CImrie\Odm\Tests\Unit\Mapping;


use CImrie\ODM\Mapping\Discriminator;
use CImrie\Odm\Tests\Models\TestUser;

class DiscriminatorBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Discriminator
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = new Discriminator();
    }

    /**
     * @test
     */
    public function can_discriminate_on_a_particular_field()
    {
        $result = $this->builder->field('name');

        $this->assertInstanceOf(Discriminator::class, $result);
        $this->assertEquals(['discriminatorField' => 'name'], $result->asArray());
    }

    public function can_use_a_discriminator_map()
    {
        $result = $this->builder
            ->withMap(['foo' => TestUser::class]);

        $this->assertInstanceOf(Discriminator::class, $result);
        $this->assertEquals(
            [
                'discriminatorMap' => ['foo' => TestUser::class]
            ],
            $result->asArray()
        );

    }

    /**
     * @test
     */
    public function can_set_default_discriminator_value()
    {
        $result = $this->builder
            ->setDefaultValue('foo');

        $this->assertInstanceOf(Discriminator::class, $result);
        $this->assertEquals(['defaultDiscriminatorValue' => 'foo'], $result->asArray());
    }
}