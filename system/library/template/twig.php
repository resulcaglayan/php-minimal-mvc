<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////

namespace Template;
final class Twig {
	private $twig;
	private $data = array();
	
	public function __construct() {
		include_once(DIR_SYS . 'library/template/Twig/Autoloader.php');
		\Twig_Autoloader::register();
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($template, $cache = false) {
		$loader = new \Twig_Loader_Filesystem(DIR_TPL);
		$config = array('autoescape' => false);
		if ($cache) {
			$config['cache'] = DIR_CACHE;
		}
		$this->twig = new \Twig_Environment($loader, $config);
		try {
			$template = $this->twig->loadTemplate($template . '.html');
			return $template->render($this->data);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $template . '!');
			exit();	
		}	
	}	
}
