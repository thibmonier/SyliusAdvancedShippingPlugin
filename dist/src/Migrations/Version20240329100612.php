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
final class Version20240329100612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_address DROP FOREIGN KEY FK_B97FF058CEBF5BEA');
        $this->addSql('ALTER TABLE sylius_address ADD CONSTRAINT FK_B97FF058CEBF5BEA FOREIGN KEY (source_order_id) REFERENCES sylius_order (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_address DROP FOREIGN KEY FK_B97FF058CEBF5BEA');
        $this->addSql('ALTER TABLE sylius_address ADD CONSTRAINT FK_B97FF058CEBF5BEA FOREIGN KEY (source_order_id) REFERENCES sylius_order (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
