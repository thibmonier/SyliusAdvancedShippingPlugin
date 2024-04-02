<?php

/*
 * This file is part of Monsieur Biz' Advanced Shipping plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319091018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_address ADD source_order_id INT DEFAULT NULL, ADD pickup_point_type VARCHAR(255) DEFAULT NULL, ADD pickup_point_code VARCHAR(255) DEFAULT NULL, ADD temporary TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE sylius_address ADD CONSTRAINT FK_B97FF058CEBF5BEA FOREIGN KEY (source_order_id) REFERENCES sylius_order (id)');
        $this->addSql('CREATE INDEX IDX_B97FF058CEBF5BEA ON sylius_address (source_order_id)');
        $this->addSql('ALTER TABLE sylius_shipment ADD advanced_shipping_metadata LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD shipping_address_provider_configuration_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD CONSTRAINT FK_5FB0EE11FABDC8CC FOREIGN KEY (shipping_address_provider_configuration_id) REFERENCES monsieurbiz_shipping_address_provider_config (id)');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD CONSTRAINT FK_5FB0EE11C54C8C93 FOREIGN KEY (type_id) REFERENCES monsieurbiz_shipping_type (id)');
        $this->addSql('CREATE INDEX IDX_5FB0EE11FABDC8CC ON sylius_shipping_method (shipping_address_provider_configuration_id)');
        $this->addSql('CREATE INDEX IDX_5FB0EE11C54C8C93 ON sylius_shipping_method (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_address DROP FOREIGN KEY FK_B97FF058CEBF5BEA');
        $this->addSql('DROP INDEX IDX_B97FF058CEBF5BEA ON sylius_address');
        $this->addSql('ALTER TABLE sylius_address DROP source_order_id, DROP pickup_point_type, DROP pickup_point_code, DROP temporary');
        $this->addSql('ALTER TABLE sylius_shipment DROP advanced_shipping_metadata');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP FOREIGN KEY FK_5FB0EE11FABDC8CC');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP FOREIGN KEY FK_5FB0EE11C54C8C93');
        $this->addSql('DROP INDEX IDX_5FB0EE11FABDC8CC ON sylius_shipping_method');
        $this->addSql('DROP INDEX IDX_5FB0EE11C54C8C93 ON sylius_shipping_method');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP shipping_address_provider_configuration_id, DROP type_id');
    }
}
