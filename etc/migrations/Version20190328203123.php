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
				`email_verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`username` varchar(50) NOT NULL,
				`password` varchar(80) DEFAULT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				`deleted_at` datetime DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `id_UNIQUE` (`id`),
				UNIQUE KEY `uuid_UNIQUE` (`uuid`),
				UNIQUE KEY `email_UNIQUE` (`email`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		");

		$this->addSql('
			INSERT INTO `identity` (`id`, `uuid`, `first_name`, `last_name`, `email`, `email_verified`, `username`, `password`, `created_at`, `updated_at`, `deleted_at`)
			VALUES
				(
					1,
					X\'37353833333137622D306438662D343036332D393133302D346265353133653332336236\',
					\'John\',
					\'Doe\',
					\'john@doe.org\',
					1,
					\'john@doe.org\',
					\'$2y$10$ekGFiuGUjpKS1jOav5CbLeCSL2lMRZqr27QnMjzy2xD7XFTtdsSbS\',
					\'2019-04-03 15:53:10\',
					\'2019-04-03 15:53:10\',
					NULL
				);
		');


	}

	public function down(Schema $schema) : void
	{
		$this->addSql('DROP TABLE identity');
	}
}
