<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////
// Registry
$registry = new Registry();
// Config
$config = new Config();
$config->load($application_config);
$registry->set('config', $config);
// Event
$event = new Event($registry);
$registry->set('event', $event);
// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		foreach ($value as $priority => $action) {
			$event->register($key, new Action($action), $priority);
		}
	}
}
// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);
// Request
$registry->set('request', new Request());
// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);
// Database
if ($config->get('db_autostart')) {
	$registry->set('db', new DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port')));
}
// Session
$session = new Session($config->get('session_engine'), $registry);
$registry->set('session', $session);
if ($config->get('session_autostart')) {
	if (isset($_COOKIE[$config->get('session_name')])) {
		$session_id = $_COOKIE[$config->get('session_name')];
	} else {
		$session_id = '';
	}
	$session->start($session_id);
	setcookie($config->get('session_name'), $session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
}
// Document
$registry->set('document', new Document());
// Route
$store = new Router($registry);
// Dispatch
$store->dispatch(new Action($config->get('action_router')), new Action($config->get('action_error')));
// Output
$response->output();