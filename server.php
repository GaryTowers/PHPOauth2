<?php
require 'vendor/autoload.php';
$dsn      = 'mysql:dbname=oauth2_db;host=localhost';
$username = 'root';
$password = '';

// error reporting enabled
ini_set('display_errors',1);error_reporting(E_ALL);

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

$server = new OAuth2\Server($storage);
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
?>