<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////

class Event {
	protected $registry;
	protected $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($trigger, Action $action, $priority = 0) {
		$this->data[] = array(
			'trigger'  => $trigger,
			'action'   => $action,
			'priority' => $priority,
		);

		$sort_order = array();

		foreach ($this->data as $key => $value) {
			$sort_order[$key] = $value['priority'];
		}

		array_multisort($sort_order, SORT_ASC, $this->data);
	}

	public function trigger($event, array $args = array()) {
		foreach ($this->data as $value) {
			if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($value['trigger'], '/')) . '/', $event)) {
				$result = $value['action']->execute($this->registry, $args);

				if (!is_null($result) && !($result instanceof Exception)) {
					return $result;
				}
			}
		}
	}

	public function unregister($trigger, $store) {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger'] && $value['action']->getId() == $store) {
				unset($this->data[$key]);
			}
		}
	}

	public function clear($trigger) {
		foreach ($this->data as $key => $value) {
			if ($trigger == $value['trigger']) {
				unset($this->data[$key]);
			}
		}
	}
}