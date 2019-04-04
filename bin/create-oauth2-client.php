#!/usr/bin/env php
<?php

chdir(dirname(__DIR__));
// Setup autoloading
require_once 'vendor/autoload.php';

$container = require 'config/container.php';

try
{
	$database = $container->get(\Doctrine\DBAL\Driver\Connection::class);

	$database->beginTransaction();

	$identifier = 'client_api';
	$secret = password_hash('secret', PASSWORD_BCRYPT);
	$name = 'Client API Oauth2 ';
	$redirectUri = '/redirect';

	$database->insert('oauth_client', [
		'identifier' => $identifier,
		'secret' => $secret,
		'name' => $name,
		'redirect_uri' => $redirectUri,
		'created_dt' => gmdate('Y-m-d h:i:s'),
		'updated_dt' => gmdate('Y-m-d h:i:s'),
	]);

	$database->commit();

	die(PHP_EOL);
}
catch (\Exception $e)
{
	$database->rollback();

	die('Something went wrong: ' . $e->getMessage() . PHP_EOL);
}
