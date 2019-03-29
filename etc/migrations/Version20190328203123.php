<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328203123 extends AbstractMigration
{
	public function getDescription() : string
	{
		return 'Create Identity Table';
	}

	public function up(Schema $schema) : void
	{
		$this->addSql("
			CREATE TABLE `identity` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`uuid` varchar(45) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
			`first_name` varchar(45) NOT NULL,
			`last_name` varchar(45) NOT NULL,
			`email` varchar(45) NOT NULL,
			`created_at` datetime NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `id_UNIQUE` (`id`),
			UNIQUE KEY `uuid_UNIQUE` (`uuid`),
			UNIQUE KEY `email_UNIQUE` (`email`)
		  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		");
	}

	public function down(Schema $schema) : void
	{
		$this->addSql('DROP TABLE identity');
	}
}
