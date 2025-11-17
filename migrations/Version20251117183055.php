<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251117183055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cow (id INT AUTO_INCREMENT NOT NULL, farm_id INT NOT NULL, code VARCHAR(255) NOT NULL, milkperweek DOUBLE PRECISION NOT NULL, foodperweek DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION NOT NULL, birthdate DATE NOT NULL, isslaughtered TINYINT(1) NOT NULL, slaughterdate DATE DEFAULT NULL, isalive TINYINT(1) NOT NULL, createdat DATE NOT NULL, updatedat DATE NOT NULL, UNIQUE INDEX UNIQ_99D43F9C77153098 (code), INDEX IDX_99D43F9C65FCFA0D (farm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE farm (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size NUMERIC(10, 2) NOT NULL, responsible VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5816D0455E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE farm_veterinarian (farm_id INT NOT NULL, veterinarian_id INT NOT NULL, INDEX IDX_499A5CC65FCFA0D (farm_id), INDEX IDX_499A5CC804C8213 (veterinarian_id), PRIMARY KEY(farm_id, veterinarian_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE veterinarian (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, crmv VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_4E5C18053697FA2C (crmv), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cow ADD CONSTRAINT FK_99D43F9C65FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id)');
        $this->addSql('ALTER TABLE farm_veterinarian ADD CONSTRAINT FK_499A5CC65FCFA0D FOREIGN KEY (farm_id) REFERENCES farm (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE farm_veterinarian ADD CONSTRAINT FK_499A5CC804C8213 FOREIGN KEY (veterinarian_id) REFERENCES veterinarian (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cow DROP FOREIGN KEY FK_99D43F9C65FCFA0D');
        $this->addSql('ALTER TABLE farm_veterinarian DROP FOREIGN KEY FK_499A5CC65FCFA0D');
        $this->addSql('ALTER TABLE farm_veterinarian DROP FOREIGN KEY FK_499A5CC804C8213');
        $this->addSql('DROP TABLE cow');
        $this->addSql('DROP TABLE farm');
        $this->addSql('DROP TABLE farm_veterinarian');
        $this->addSql('DROP TABLE veterinarian');
    }
}
