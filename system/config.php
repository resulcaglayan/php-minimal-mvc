<?php
///////////////////////////////////////////////////
// Author: Resul ÇAĞLAYAN
// Project: MVC Framework
///////////////////////////////////////////////////
// HTTP SETTING
define('HTTP_SERVER', 'http://localhost/php-minimal-mvc/');
///////////////////////////////////////////////////
// DIR SETTINGS
define('DIR_APP', 'C:/xampp/htdocs/php-minimal-mvc/'); // Projenin kurulu olduğu dizin
define('DIR_SYS', 'C:/xampp/htdocs/php-minimal-mvc/system/'); // system klasörünün bulunduğu dizin
define('DIR_TPL', DIR_APP . 'view/');
define('DIR_CNF', DIR_SYS . 'config/');
define('DIR_SES', DIR_SYS . 'session/');
///////////////////////////////////////////////////
// DB SETTINGS
define('DB_STATUS', false); // Database kullanımı durumunda true olmalı
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');
define('DB_PORT', '3306');
///////////////////////////////////////////////////
// OTHER SETTINGS
define('SET_TIMEZONE', 'Europe/Istanbul'); // Global tarih için UTC kullanılabilir
define('SET_ERROR', E_ALL); // E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
define('SET_PHP', '5.4.0'); // minimum php versiyon
define('SET_SESSION', 'file'); // file veya db (file kullanılması durumunda system/session dizini chmod 777 olmalı)
///////////////////////////////////////////////////