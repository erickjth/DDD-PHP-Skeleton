<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403141848 extends AbstractMigration
{
	public function getDescription() : string
	{
		return 'Create OAuth2 Schema';
	}

	public function up(Schema $schema) : void
	{
		$this->addSql("
			CREATE TABLE `oauth_client` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`identifier` varchar(40) NOT NULL,
				`secret` varchar(100) NOT NULL DEFAULT '',
				`name` varchar(100) NOT NULL,
				`identity_id` int(10) unsigned DEFAULT NULL,
				`redirect_uri` text NOT NULL,
				`created_dt` datetime NOT NULL,
				`updated_dt` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `identifier_UNIQUE` (`identifier`),
				UNIQUE KEY `id_UNIQUE` (`id`),
				KEY `fk_oauth_client_identity_idx` (`identity_id`),
				CONSTRAINT `fk_oauth_client_identity` FOREIGN KEY (`identity_id`) REFERENCES `identity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

			CREATE TABLE `oauth_access_token` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`identifier` varchar(80) NOT NULL,
				`revoked` tinyint(1) NOT NULL DEFAULT '0',
				`scope` text,
				`client_id` int(10) unsigned DEFAULT NULL,
				`identity_id` int(10) unsigned DEFAULT NULL,
				`created_dt` datetime NOT NULL,
				`expiration_dt` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `identifier_UNIQUE` (`identifier`),
				UNIQUE KEY `id_UNIQUE` (`id`),
				KEY `FK_access_token_client_idx` (`client_id`),
				KEY `fk_access_token_identity_idx` (`identity_id`),
				CONSTRAINT `fk_access_token_client` FOREIGN KEY (`client_id`) REFERENCES `oauth_client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_access_token_identity` FOREIGN KEY (`identity_id`) REFERENCES `identity` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

			CREATE TABLE `oauth_authorization_code` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`identifier` varchar(80) NOT NULL,
				`revoked` tinyint(4) NOT NULL DEFAULT '0',
				`redirect_uri` text NOT NULL,
				`client_id` int(10) unsigned NOT NULL,
				`scope` text,
				`created_dt` datetime NOT NULL,
				`expiration_dt` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `code_UNIQUE` (`identifier`),
				KEY `fk_authorization_code_client_idx` (`client_id`),
				CONSTRAINT `fk_authorization_code_client` FOREIGN KEY (`client_id`) REFERENCES `oauth_client` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


			CREATE TABLE `oauth_refresh_token` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`identifier` varchar(80) NOT NULL,
				`revoked` tinyint(4) NOT NULL DEFAULT '0',
				`access_token_id` int(10) unsigned NOT NULL,
				`created_dt` datetime NOT NULL,
				`expiration_dt` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `identifier_UNIQUE` (`identifier`),
				KEY `fk_refresh_token_access_token_idx` (`access_token_id`),
				CONSTRAINT `fk_refresh_token_access_token` FOREIGN KEY (`access_token_id`) REFERENCES `oauth_access_token` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


			CREATE TABLE `oauth_scopes` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`identifier` varchar(80) NOT NULL,
				`description` text,
				`created_dt` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `identifier_UNIQUE` (`identifier`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		");

		$this->addSql('
			INSERT INTO `oauth_client` (`id`, `identifier`, `secret`, `name`, `identity_id`, `redirect_uri`, `created_dt`, `updated_dt`)
			VALUES (
				1,
				\'client_api\',
				\'$2y$10$BV91oAoWsFDj1KW8K9xPXe8qzLA9TauLO.z.kvHOZr2l0Rboe4uL6\',
				\'Client API Oauth2\',
				1,
				\'/redirect\',
				\'2019-04-04 12:29:54\',
				\'2019-04-04 12:29:54\'
			);
		');
	}

	public function down(Schema $schema) : void
	{
			$this->addSql('DROP TABLE oauth_client');
			$this->addSql('DROP TABLE oauth_access_token');
			$this->addSql('DROP TABLE oauth_authorization_code');
			$this->addSql('DROP TABLE oauth_refresh_token');
			$this->addSql('DROP TABLE oauth_scopes');
	}
}
