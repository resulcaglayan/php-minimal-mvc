<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////

final class Loader {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function controller($store, $data = array()) {
		// Sanitize the call
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);
		
		// Keep the original trigger
		$trigger = $store;
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('controller/' . $trigger . '/before', array(&$store, &$data));
		
		// Make sure its only the last event that returns an output if required.
		if ($result != null && !$result instanceof Exception) {
			$output = $result;
		} else {
			$action = new Action($store);
			$output = $action->execute($this->registry, array(&$data));
		}
		
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('controller/' . $trigger . '/after', array(&$store, &$data, &$output));
		
		if ($result && !$result instanceof Exception) {
			$output = $result;
		}

		if (!$output instanceof Exception) {
			return $output;
		}
	}

	public function model($store) {
		// Sanitize the call
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);
		
		if (!$this->registry->has('model_' . str_replace('/', '_', $store))) {
			$file  = DIR_APP . 'model/' . $store . '.php';
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $store);
			
			if (is_file($file)) {
				include_once($file);
	
				$proxy = new Proxy();
				
				// Overriding models is a little harder so we have to use PHP's magic methods
				// In future version we can use runkit
				foreach (get_class_methods($class) as $method) {
					$proxy->{$method} = $this->callback($this->registry, $store . '/' . $method);
					
				}
				$this->registry->set('model_' . str_replace('/', '_', (string)$store), $proxy);
			} else {
				throw new \Exception('Error: Could not load model ' . $store . '!');
			}
		}
	}

	public function view($store, $data = array()) {
		// Sanitize the call
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);
		
		// Keep the original trigger
		$trigger = $store;
		
		// Template contents. Not the output!
		$template = '';
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('view/' . $trigger . '/before', array(&$store, &$data, &$template));
		
		// Make sure its only the last event that returns an output if required.
		if ($result && !$result instanceof Exception) {
			$output = $result;
		} else {
			$template = new Template($this->registry->get('config')->get('template_engine'));
				
			foreach ($data as $key => $value) {
				$template->set($key, $value);
			}

			$output = $template->render($this->registry->get('config')->get('template_directory') . $store, $this->registry->get('config')->get('template_cache'));		
		}
		
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('view/' . $trigger . '/after', array(&$store, &$data, &$output));
		
		if ($result && !$result instanceof Exception) {
			$output = $result;
		}
		
		return $output;
	}

	public function library($store) {
		// Sanitize the call
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);
			
		$file = DIR_SYS . 'library/' . $store . '.php';
		$class = str_replace('/', '\\', $store);

		if (is_file($file)) {
			include_once($file);

			$this->registry->set(basename($store), new $class($this->registry));
		} else {
			throw new \Exception('Error: Could not load library ' . $store . '!');
		}
	}
	
	public function helper($store) {
		$file = DIR_SYS . 'helper/' . preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store) . '.php';

		if (is_file($file)) {
			include_once($file);
		} else {
			throw new \Exception('Error: Could not load helper ' . $store . '!');
		}
	}

	public function config($store) {
		$this->registry->get('event')->trigger('config/' . $store . '/before', array(&$store));
		
		$this->registry->get('config')->load($store);
		
		$this->registry->get('event')->trigger('config/' . $store . '/after', array(&$store));
	}

	public function language($store, $key = '') {
		// Sanitize the call
		$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);
		
		// Keep the original trigger
		$trigger = $store;
				
		$result = $this->registry->get('event')->trigger('language/' . $trigger . '/before', array(&$store, &$key));
		
		if ($result && !$result instanceof Exception) {
			$output = $result;
		} else {
			$output = $this->registry->get('language')->load($store, $key);
		}
		
		$result = $this->registry->get('event')->trigger('language/' . $trigger . '/after', array(&$store, &$key, &$output));
		
		if ($result && !$result instanceof Exception) {
			$output = $result;
		}
				
		return $output;
	}
	
	protected function callback($registry, $store) {
		return function($args) use($registry, $store) {
			static $model;
			
			$store = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$store);

			// Keep the original trigger
			$trigger = $store;
					
			// Trigger the pre events
			$result = $registry->get('event')->trigger('model/' . $trigger . '/before', array(&$store, &$args));
			
			if ($result && !$result instanceof Exception) {
				$output = $result;
			} else {
				$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($store, 0, strrpos($store, '/')));
				
				$key = substr($store, 0, strrpos($store, '/'));
				
				if (!isset($model[$key])) {
					$model[$key] = new $class($registry);
				}
				
				$method = substr($store, strrpos($store, '/') + 1);
				
				$callable = array($model[$key], $method);
	
				if (is_callable($callable)) {
					$output = call_user_func_array($callable, $args);
				} else {
					throw new \Exception('Error: Could not call model/' . $store . '!');
				}					
			}
			
			// Trigger the post events
			$result = $registry->get('event')->trigger('model/' . $trigger . '/after', array(&$store, &$args, &$output));
			
			if ($result && !$result instanceof Exception) {
				$output = $result;
			}
						
			return $output;
		};
	}	
}