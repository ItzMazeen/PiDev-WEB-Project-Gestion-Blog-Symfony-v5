<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308192319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier_medical (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, vaccins VARCHAR(255) NOT NULL, maladies VARCHAR(255) NOT NULL, allergies VARCHAR(255) NOT NULL, analyses VARCHAR(255) NOT NULL, intervention_chirurgicale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3581EE629D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ficheconsultation (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, dossier_medical_id INT DEFAULT NULL, date_consultation DATE NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, traitement VARCHAR(255) NOT NULL, reccomendation VARCHAR(255) NOT NULL, INDEX IDX_EB8C06E387F4FB17 (doctor_id), INDEX IDX_EB8C06E37750B79F (dossier_medical_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, type_service_id INT DEFAULT NULL, nom_service VARCHAR(255) NOT NULL, proprietaire VARCHAR(255) NOT NULL, id_type VARCHAR(255) NOT NULL, prix NUMERIC(10, 0) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_E19D9AD2F05F7FC3 (type_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_service (id INT AUTO_INCREMENT NOT NULL, nom_type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE629D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ficheconsultation ADD CONSTRAINT FK_EB8C06E387F4FB17 FOREIGN KEY (doctor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ficheconsultation ADD CONSTRAINT FK_EB8C06E37750B79F FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2F05F7FC3 FOREIGN KEY (type_service_id) REFERENCES type_service (id)');
        $this->addSql('ALTER TABLE fiche_consultation DROP FOREIGN KEY FK_CAD69893611C0C56');
        $this->addSql('DROP TABLE fiche_consultation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche_consultation (id INT AUTO_INCREMENT NOT NULL, dossier_id INT DEFAULT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, traitements VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, recommendation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, spécialité_docteur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_consultation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CAD69893611C0C56 (dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE fiche_consultation ADD CONSTRAINT FK_CAD69893611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE629D86650F');
        $this->addSql('ALTER TABLE ficheconsultation DROP FOREIGN KEY FK_EB8C06E387F4FB17');
        $this->addSql('ALTER TABLE ficheconsultation DROP FOREIGN KEY FK_EB8C06E37750B79F');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2F05F7FC3');
        $this->addSql('DROP TABLE dossier_medical');
        $this->addSql('DROP TABLE ficheconsultation');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE type_service');
    }
}
