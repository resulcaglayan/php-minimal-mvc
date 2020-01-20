<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////
// Site
$_['site_url'] = HTTP_SERVER;
///////////////////////////////////////////////////
// Database
$_['db_autostart'] = DB_STATUS;
$_['db_engine']    = DB_DRIVER;
$_['db_hostname']  = DB_HOSTNAME;
$_['db_username']  = DB_USERNAME;
$_['db_password']  = DB_PASSWORD;
$_['db_database']  = DB_DATABASE;
$_['db_port']      = DB_PORT;
///////////////////////////////////////////////////
// Session
$_['session_engine']    = SET_SESSION;
$_['session_autostart'] = true;
$_['session_name']      = 'MVCSESSID';
///////////////////////////////////////////////////
// Template
$_['template_engine'] = 'twig';
///////////////////////////////////////////////////
// Action
$_['action_default'] = 'index';
$_['action_router']  = 'router';
$_['action_error']   = '404';
///////////////////////////////////////////////////
// Actions
$_['action_pre_action'] = array(
	//'startup/startup',
);
///////////////////////////////////////////////////