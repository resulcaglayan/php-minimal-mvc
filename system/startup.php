<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////
// Error Reporting
error_reporting(SET_ERROR);
// Default Time Zone
date_default_timezone_set(SET_TIMEZONE);
// Check Version
if (version_compare(phpversion(), SET_PHP, '<') == true) {
	exit(SET_PHP . '+ Required');
}
// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}
if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}
if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}
// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || (isset($_SERVER['HTTPS']) && (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}
function library($class) {
	$file = DIR_SYS . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

	if (is_file($file)) {
		include_once $file;

		return true;
	} else {
		return false;
	}
}
spl_autoload_register('library');
spl_autoload_extensions('.php');
// Engine
require_once DIR_SYS . 'engine/action.php';
require_once DIR_SYS . 'engine/controller.php';
require_once DIR_SYS . 'engine/event.php';
require_once DIR_SYS . 'engine/router.php';
require_once DIR_SYS . 'engine/loader.php';
require_once DIR_SYS . 'engine/model.php';
require_once DIR_SYS . 'engine/registry.php';
require_once DIR_SYS . 'engine/proxy.php';
require_once DIR_SYS . 'engine/utf8.php';
function start($application_config) {
	require_once DIR_SYS . 'framework.php';
}