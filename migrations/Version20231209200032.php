<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231209200032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE coupon_id_seq INCREMENT BY 1 MINVALUE 1 START 10001');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 10001');
        $this->addSql('CREATE SEQUENCE tax_id_seq INCREMENT BY 1 MINVALUE 1 START 10001');
        $this->addSql('CREATE TABLE coupon (id INT NOT NULL, code VARCHAR(20) NOT NULL, type INT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64BF3F0277153098 ON coupon (code)');
        $this->addSql('CREATE TABLE payment (id UUID NOT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, amount INT NOT NULL, gateway VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payment_calc_result JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN payment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.completed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tax (id INT NOT NULL, code VARCHAR(2) NOT NULL, pattern VARCHAR(20) NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E81BA7677153098 ON tax (code)');

        $this->addSql("INSERT INTO product (id, name, price) VALUES(1001, 'Iphone', 10000)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES(1002, 'Headphones', 2000)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES(1003, 'Phone cover', 1000)");
        $this->addSql("INSERT INTO product (id, name, price) VALUES(1004, 'Iphone ultra cover', 100000)");

        $this->addSql("INSERT INTO coupon (id, code, type, value) VALUES(1001, 'DF10', 1, 1000)");
        $this->addSql("INSERT INTO coupon (id, code, type, value) VALUES(1002, 'DP6', 2, 6)");
        $this->addSql("INSERT INTO coupon (id, code, type, value) VALUES(1003, 'DF8', 1, 8)");

        $this->addSql("INSERT INTO tax (id, code, pattern, value) VALUES (1001, 'DE', 'XXXXXXXXX', 19)");
        $this->addSql("INSERT INTO tax (id, code, pattern, value) VALUES (1002, 'IT', 'XXXXXXXXXXX', 22)");
        $this->addSql("INSERT INTO tax (id, code, pattern, value) VALUES (1003, 'GR', 'XXXXXXXXX', 24)");
        $this->addSql("INSERT INTO tax (id, code, pattern, value) VALUES (1004, 'FR', 'YYXXXXXXXXX', 20)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE coupon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tax_id_seq CASCADE');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE tax');
    }
}
