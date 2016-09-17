<?php


namespace LaravelDoctrine\ODM\Common\Registries;


use Doctrine\Common\EventSubscriber;

/**
 * Class ListenerRegistry
 *
 * Manages the in-memory storage and retrieval of fully instantiated listeners and subscribers, ready for use
 * in an event manager.
 *
 * @package LaravelDoctrine\ODM\Common
 */
class ListenerRegistry {

	/**
	 * @var array
	 */
	protected $subscribers = [];

	/**
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * @param array $listeners
	 */
	public function addListeners(array $listeners)
	{
		foreach($listeners as $listener)
		{
			$this->addListener($listener);
		}
	}

	/**
	 * @param $listener
	 */
	public function addListener($listener)
	{
		$this->listeners[get_class($listener)] = $listener;
	}

	/**
	 * @param array $subscribers
	 */
	public function addSubscribers(array $subscribers)
	{
		foreach($subscribers as $subscriber)
		{
			$this->addSubscriber($subscriber);
		}
	}

	/**
	 * @param EventSubscriber $subscriber
	 */
	public function addSubscriber(EventSubscriber $subscriber)
	{
		$this->subscribers[get_class($subscriber)] = $subscriber;
	}

	/**
	 * @return array
	 */
	public function getListeners()
	{
		return $this->listeners;
	}

	/**
	 * @return array
	 */
	public function getSubscribers()
	{
		return $this->subscribers;
	}

	/**
	 * @param $class
	 *
	 * @return mixed $listener
	 */
	public function getListener($class)
	{
		return array_get($this->listeners, $class);
	}

	/**
	 * @param $class
	 *
	 * @return EventSubscriber
	 */
	public function getSubscriber($class)
	{
		return array_get($this->subscribers, $class);
	}
}