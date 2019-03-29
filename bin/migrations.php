#!/usr/bin/env php
<?php

chdir(dirname(__DIR__));
// Setup autoloading
require_once 'vendor/autoload.php';

$container = require 'config/container.php';

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Tools\Console\Command;
use Doctrine\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

$entityManager = $container->get('doctrine.entitymanager');
$connection = $container->get('doctrine.connection');

$configuration = new Configuration($connection);
$configuration->setName('API Migrations');
$configuration->setMigrationsNamespace('App\Migrations');
$configuration->setMigrationsTableName('doctrine_migration_versions');
$configuration->setMigrationsColumnName('version');
$configuration->setMigrationsColumnLength(255);
$configuration->setMigrationsExecutedAtColumnName('executed_at');
$configuration->setMigrationsDirectory(__DIR__ . '/../etc/migrations');
$configuration->setAllOrNothing(true);

$helperSet = new HelperSet();
$helperSet->set(new QuestionHelper(), 'question');
$helperSet->set(new ConnectionHelper($connection), 'db');
$helperSet->set(new ConfigurationHelper($connection, $configuration));
$helperSet->set(new EntityManagerHelper($entityManager), 'em');

$cli = new Application('Doctrine Migrations');
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$cli->addCommands(array(
	new Command\DumpSchemaCommand(),
	new Command\ExecuteCommand(),
	new Command\GenerateCommand(),
	new Command\LatestCommand(),
	new Command\MigrateCommand(),
	new Command\RollupCommand(),
	new Command\StatusCommand(),
	new Command\VersionCommand(),
	new Command\DiffCommand()
));

$cli->run();
