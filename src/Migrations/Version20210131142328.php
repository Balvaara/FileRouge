<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131142328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE affectaion (id INT AUTO_INCREMENT NOT NULL, comptes_id INT NOT NULL, user_con_id INT DEFAULT NULL, users_id INT NOT NULL, date_afect DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_331D556DDCED588B (comptes_id), INDEX IDX_331D556D36BBBE4 (user_con_id), INDEX IDX_331D556D67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, usercreate_id INT NOT NULL, partenaire_id INT NOT NULL, numero VARCHAR(255) NOT NULL, solde INT NOT NULL, datecreate DATETIME NOT NULL, INDEX IDX_CFF6526020537CE3 (usercreate_id), INDEX IDX_CFF6526098DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, numero_contrat VARCHAR(255) NOT NULL, libelle LONGTEXT NOT NULL, therme LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, comptes_id INT NOT NULL, user_id INT NOT NULL, montant INT NOT NULL, datedepot DATETIME NOT NULL, INDEX IDX_47948BBCDCED588B (comptes_id), INDEX IDX_47948BBCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE part (id INT AUTO_INCREMENT NOT NULL, etat DOUBLE PRECISION NOT NULL, systeme DOUBLE PRECISION NOT NULL, dep DOUBLE PRECISION NOT NULL, ret DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, contrats_id INT NOT NULL, ninea LONGTEXT NOT NULL, register LONGTEXT NOT NULL, adresse VARCHAR(255) NOT NULL, tel INT NOT NULL, description VARCHAR(255) NOT NULL, localite VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_32FFA3736A6193D6 (contrats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, bon_inf INT NOT NULL, born_sup INT NOT NULL, frais INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, comptes_dep_id INT DEFAULT NULL, compte_retraits_id INT DEFAULT NULL, retait_use_id INT DEFAULT NULL, depot_use_id INT DEFAULT NULL, nomdep VARCHAR(255) DEFAULT NULL, teldep VARCHAR(255) DEFAULT NULL, montant INT DEFAULT NULL, datetransaction DATETIME DEFAULT NULL, tarifs INT DEFAULT NULL, telrep VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, piece VARCHAR(255) DEFAULT NULL, part_etat DOUBLE PRECISION DEFAULT NULL, part_systeme DOUBLE PRECISION DEFAULT NULL, part_dep DOUBLE PRECISION DEFAULT NULL, part_ret DOUBLE PRECISION DEFAULT NULL, date_ret DATETIME DEFAULT NULL, nom_recepteur VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, INDEX IDX_723705D16EC704A0 (comptes_dep_id), INDEX IDX_723705D17BCE5716 (compte_retraits_id), INDEX IDX_723705D168D1F6DF (retait_use_id), INDEX IDX_723705D189737BC5 (depot_use_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT NOT NULL, partenaire_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom_complet VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, photo LONGBLOB DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649275ED078 (profil_id), INDEX IDX_8D93D64998DE13AC (partenaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE affectaion ADD CONSTRAINT FK_331D556DDCED588B FOREIGN KEY (comptes_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE affectaion ADD CONSTRAINT FK_331D556D36BBBE4 FOREIGN KEY (user_con_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE affectaion ADD CONSTRAINT FK_331D556D67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526020537CE3 FOREIGN KEY (usercreate_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526098DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCDCED588B FOREIGN KEY (comptes_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE partenaire ADD CONSTRAINT FK_32FFA3736A6193D6 FOREIGN KEY (contrats_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D16EC704A0 FOREIGN KEY (comptes_dep_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17BCE5716 FOREIGN KEY (compte_retraits_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D168D1F6DF FOREIGN KEY (retait_use_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D189737BC5 FOREIGN KEY (depot_use_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998DE13AC FOREIGN KEY (partenaire_id) REFERENCES partenaire (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectaion DROP FOREIGN KEY FK_331D556DDCED588B');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCDCED588B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D16EC704A0');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D17BCE5716');
        $this->addSql('ALTER TABLE partenaire DROP FOREIGN KEY FK_32FFA3736A6193D6');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526098DE13AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998DE13AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE affectaion DROP FOREIGN KEY FK_331D556D36BBBE4');
        $this->addSql('ALTER TABLE affectaion DROP FOREIGN KEY FK_331D556D67B3B43D');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526020537CE3');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCA76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D168D1F6DF');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D189737BC5');
        $this->addSql('DROP TABLE affectaion');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE part');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE tarif');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
    }
}
