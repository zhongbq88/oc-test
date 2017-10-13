<?php
// HTTP

define('HTTP_SERVER', 'https://127.0.0.1/');

// HTTPS
define('HTTPS_SERVER', 'https://127.0.0.1/');

// DIR
define('DIR_APPLICATION', 'D:/xampp/htdocs/op/oc-test/catalog/');
define('DIR_SYSTEM', 'D:/xampp/htdocs/op/oc-test/system/');
define('DIR_IMAGE', 'D:/xampp/htdocs/op/oc-test/image/');
define('DIR_STORAGE', 'D:/xampp/htdocs/op/oc-test/storage/');

define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');

define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'opencart');

define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

//shopify
define('REDIRECTION_URL', HTTPS_SERVER.'index.php?route=shopify/connect');
define('SHOPIFY_APP_API_KEY', '960bd2566abc4ce62a0a0c2eb13d7aa9');
define('SHOPIFY_APP_SHARED_SECRET', 'ed18156d8d7df8fff567fd02c2703b97');
