<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330103150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lane (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, has_bumpers TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, price NUMERIC(6, 2) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, applied_rate NUMERIC(6, 2) NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, number_of_adults INT NOT NULL, number_of_children INT NOT NULL, total_price NUMERIC(8, 2) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT NOT NULL, lane_id INT NOT NULL, tariff_id INT NOT NULL, snack_package_id INT DEFAULT NULL, party_package_id INT DEFAULT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955A128F72F (lane_id), INDEX IDX_42C8495592348FD2 (tariff_id), INDEX IDX_42C84955B2F3EB8A (snack_package_id), INDEX IDX_42C84955FC3756B3 (party_package_id), INDEX idx_availability (lane_id, start_time, end_time), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tariff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, day_range VARCHAR(10) NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, price_per_hour NUMERIC(6, 2) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A128F72F FOREIGN KEY (lane_id) REFERENCES lane (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495592348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B2F3EB8A FOREIGN KEY (snack_package_id) REFERENCES package (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FC3756B3 FOREIGN KEY (party_package_id) REFERENCES package (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A128F72F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495592348FD2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B2F3EB8A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FC3756B3');
        $this->addSql('DROP TABLE lane');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('DROP TABLE `user`');
    }
}
