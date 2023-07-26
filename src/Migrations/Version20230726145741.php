<?php

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedShippingPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726145741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_shipping_address_provider_config (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9F1E9492C4739C77153098 (provider, code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_address_provider_config_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_492D725B2C2AC5D3 (translatable_id), UNIQUE INDEX mbiz_shipping_address_provider_conf_trans_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_calculator_config (id INT AUTO_INCREMENT NOT NULL, calculator VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_13E2971B247990C277153098 (calculator, code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_calculator_config_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_C128F94C2C2AC5D3 (translatable_id), UNIQUE INDEX monsieurbiz_shipping_calculator_config_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_map_provider_config (id INT AUTO_INCREMENT NOT NULL, provider VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_65E31ACA92C4739C77153098 (provider, code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_map_provider_config_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_D63F29362C2AC5D3 (translatable_id), UNIQUE INDEX mbiz_shipping_map_provider_conf_trans_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_type (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monsieurbiz_shipping_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_6E0D349C2C2AC5D3 (translatable_id), UNIQUE INDEX monsieurbiz_shipping_type_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_address_provider_config_translation ADD CONSTRAINT FK_492D725B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_shipping_address_provider_config (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_calculator_config_translation ADD CONSTRAINT FK_C128F94C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_shipping_calculator_config (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_map_provider_config_translation ADD CONSTRAINT FK_D63F29362C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_shipping_map_provider_config (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_type_translation ADD CONSTRAINT FK_6E0D349C2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES monsieurbiz_shipping_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_shipping_address_provider_config_translation DROP FOREIGN KEY FK_492D725B2C2AC5D3');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_calculator_config_translation DROP FOREIGN KEY FK_C128F94C2C2AC5D3');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_map_provider_config_translation DROP FOREIGN KEY FK_D63F29362C2AC5D3');
        $this->addSql('ALTER TABLE monsieurbiz_shipping_type_translation DROP FOREIGN KEY FK_6E0D349C2C2AC5D3');
        $this->addSql('DROP TABLE monsieurbiz_shipping_address_provider_config');
        $this->addSql('DROP TABLE monsieurbiz_shipping_address_provider_config_translation');
        $this->addSql('DROP TABLE monsieurbiz_shipping_calculator_config');
        $this->addSql('DROP TABLE monsieurbiz_shipping_calculator_config_translation');
        $this->addSql('DROP TABLE monsieurbiz_shipping_map_provider_config');
        $this->addSql('DROP TABLE monsieurbiz_shipping_map_provider_config_translation');
        $this->addSql('DROP TABLE monsieurbiz_shipping_type');
        $this->addSql('DROP TABLE monsieurbiz_shipping_type_translation');
    }
}
