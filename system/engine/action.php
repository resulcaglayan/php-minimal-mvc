<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////

class Action {
	private $id;
	private $store;
	private $method = 'index';

	public function __construct($store) {
		$this->id = $store;
		
		$parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store));
		while ($parts) {
			$file = DIR_APP . 'controller/' . implode('/', $parts) . '.php';

			if (is_file($file)) {
				$this->store = implode('/', $parts);		
				break;
			} else {
				$this->method = array_pop($parts);
			}
		}
	}

	public function getId() {
		return $this->id;
	}

	public function execute($registry, array $args = array()) {
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		$file  = DIR_APP . 'controller/' . $this->store . '.php';	
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $this->store);
		
		if (is_file($file)) {
			include_once($file);
		
			$controller = new $class($registry);
		} else {
			return new \Exception('Error: Could not call ' . $this->store . '/' . $this->method . '!');
		}
		
		$reflection = new ReflectionClass($class);
		
		if ($reflection->hasMethod($this->method) && $reflection->getMethod($this->method)->getNumberOfRequiredParameters() <= count($args)) {
			return call_user_func_array(array($controller, $this->method), $args);
		} else {
			return new \Exception('Error: Could not call ' . $this->store . '/' . $this->method . '!');
		}
	}
}
