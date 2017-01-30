<?php


namespace CImrie\ODM\Common\Registries;


use Doctrine\Common\EventSubscriber;

/**
 * Class ListenerRegistry
 *
 * Manages the in-memory storage and retrieval of fully instantiated listeners and subscribers, ready for use
 * in an event manager.
 *
 * @package CImrie\ODM\Common
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
     * @return $this
	 */
	public function addListeners(array $listeners)
	{
		foreach($listeners as $listener)
		{
			$this->addListener($listener);
		}

		return $this;
	}

    /**
     * @param $listener
     * @return $this
     */
	public function addListener($listener)
	{
		$this->listeners[get_class($listener)] = $listener;

		return $this;
	}

    /**
     * @param EventSubscriber[] $subscribers
     * @return $this
     */
	public function addSubscribers(array $subscribers)
	{
		foreach($subscribers as $subscriber)
		{
			$this->addSubscriber($subscriber);
		}

		return $this;
	}

    /**
     * @param EventSubscriber $subscriber
     * @return $this
     */
	public function addSubscriber(EventSubscriber $subscriber)
	{
		$this->subscribers[get_class($subscriber)] = $subscriber;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getListeners()
	{
		return $this->listeners;
	}

	/**
	 * @return EventSubscriber[]
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