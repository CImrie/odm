<?php

namespace Tests\Common\Registries;


use Doctrine\Common\EventSubscriber;
use CImrie\ODM\Common\Registries\ListenerRegistry;
use Mockery as m;

class ListenerRegistryTest extends \PHPUnit_Framework_TestCase  {

	/**
	 * @var ListenerRegistry
	 */
	protected $registry;

	protected function setUp()
	{
		$this->registry = new ListenerRegistry();
	}

	public function test_can_add_event_listener()
	{
		$listener = m::mock(ListenerStub::class);
		$this->registry->addListener($listener);

		$this->assertEquals($listener, $this->registry->getListener(get_class($listener)));
		$this->assertNull($this->registry->getListener(SubscriberStub::class));
	}

	public function test_can_add_multiple_event_listeners()
	{
		$listeners = [
			m::mock(ListenerStub::class),
			m::mock(AnotherListenerStub::class),
		];

		$this->registry->addListeners($listeners);

		$this->assertCount(2, $this->registry->getListeners());
	}

	public function test_can_add_event_subscriber()
	{
		$subscriber = m::mock(SubscriberStub::class);
		$this->registry->addSubscriber($subscriber);

		$this->assertEquals($subscriber, $this->registry->getSubscriber(get_class($subscriber)));
		$this->assertNull($this->registry->getListener(ListenerStub::class));
	}

	public function test_can_add_multiple_event_subscribers()
	{
		$subscribers = [
			m::mock(SubscriberStub::class),
			m::mock(AnotherSubscriberStub::class),
		];

		$this->registry->addSubscribers($subscribers);

		$this->assertCount(2, $this->registry->getSubscribers());
	}
}

class ListenerStub {}
class AnotherListenerStub {}
abstract class SubscriberStub implements EventSubscriber {}
abstract class AnotherSubscriberStub implements EventSubscriber {}