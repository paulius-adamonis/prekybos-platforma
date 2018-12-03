<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181204133814 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE KOMENTARAS CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ATSILIEPIMAS CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE KLAUSIMAS CHANGE fk_atsakytojas fk_atsakytojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ZINUTE CHANGE fk_rasytojas fk_rasytojas INT DEFAULT NULL, CHANGE fk_gavejas fk_gavejas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE PARD_PREKIU_KATEGORIJU_PRIKLAUSYMAS CHANGE fk_parduotuves_prekes_kategorija fk_parduotuves_prekes_kategorija INT DEFAULT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ISLAIDOS CHANGE fk_islaidos_tipas fk_islaidos_tipas INT DEFAULT NULL, CHANGE fk_sandelis fk_sandelis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE PREKIU_PRIKLAUSYMAS CHANGE fk_sandelis fk_sandelis INT DEFAULT NULL, CHANGE fk_kokybe fk_kokybe INT DEFAULT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VARTOTOJO_ATSILIEPIMAS CHANGE fk_gavejas fk_gavejas INT DEFAULT NULL, CHANGE fk_rasytojas fk_rasytojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VARTOTOJAS CHANGE role role LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE TURGAUS_PARDAVIMAS CHANGE fk_pardavejas fk_pardavejas INT DEFAULT NULL, CHANGE fk_pirkejas fk_pirkejas INT DEFAULT NULL, CHANGE fk_turgaus_preke fk_turgaus_preke INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SANDELIU_PRIKLAUSYMAS CHANGE fk_sandelis fk_sandelis INT DEFAULT NULL, CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE PREKIU_UZSAKYMAS CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL, CHANGE fk_sandelis fk_sandelis INT DEFAULT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT DEFAULT NULL');
        $this->addSql('ALTER TABLE PARD_PARDAVIMAS CHANGE fk_parduotuves_preke fk_parduotuves_preke INT DEFAULT NULL, CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE TURG_PREKES_KATEGORIJA CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL, CHANGE fk_pardavimo_tipas fk_pardavimo_tipas INT DEFAULT NULL');
        $this->addSql('ALTER TABLE PREKIU_NARSYMO_ISTORIJA CHANGE fk_vartotojas fk_vartotojas INT DEFAULT NULL, CHANGE skaitliukas skaitliukas INT NOT NULL');
        $this->addSql('ALTER TABLE TURGAUS_PREKE CHANGE fk_turg_prekes_kategorija fk_turg_prekes_kategorija INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ATSILIEPIMAS CHANGE fk_vartotojas fk_vartotojas INT NOT NULL');
        $this->addSql('ALTER TABLE ISLAIDOS CHANGE fk_islaidos_tipas fk_islaidos_tipas INT NOT NULL, CHANGE fk_sandelis fk_sandelis INT NOT NULL');
        $this->addSql('ALTER TABLE KLAUSIMAS CHANGE fk_atsakytojas fk_atsakytojas INT NOT NULL');
        $this->addSql('ALTER TABLE KOMENTARAS CHANGE fk_vartotojas fk_vartotojas INT NOT NULL');
        $this->addSql('ALTER TABLE PARD_PARDAVIMAS CHANGE fk_vartotojas fk_vartotojas INT NOT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT NOT NULL');
        $this->addSql('ALTER TABLE PARD_PREKIU_KATEGORIJU_PRIKLAUSYMAS CHANGE fk_parduotuves_prekes_kategorija fk_parduotuves_prekes_kategorija INT NOT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT NOT NULL');
        $this->addSql('ALTER TABLE PREKIU_NARSYMO_ISTORIJA CHANGE fk_vartotojas fk_vartotojas INT NOT NULL, CHANGE skaitliukas skaitliukas INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE PREKIU_PRIKLAUSYMAS CHANGE fk_parduotuves_preke fk_parduotuves_preke INT NOT NULL, CHANGE fk_kokybe fk_kokybe INT NOT NULL, CHANGE fk_sandelis fk_sandelis INT NOT NULL');
        $this->addSql('ALTER TABLE PREKIU_UZSAKYMAS CHANGE fk_sandelis fk_sandelis INT NOT NULL, CHANGE fk_vartotojas fk_vartotojas INT NOT NULL, CHANGE fk_parduotuves_preke fk_parduotuves_preke INT NOT NULL');
        $this->addSql('ALTER TABLE SANDELIU_PRIKLAUSYMAS CHANGE fk_sandelis fk_sandelis INT NOT NULL, CHANGE fk_vartotojas fk_vartotojas INT NOT NULL');
        $this->addSql('ALTER TABLE TURGAUS_PARDAVIMAS CHANGE fk_pardavejas fk_pardavejas INT NOT NULL, CHANGE fk_pirkejas fk_pirkejas INT NOT NULL, CHANGE fk_turgaus_preke fk_turgaus_preke INT NOT NULL');
        $this->addSql('ALTER TABLE TURGAUS_PREKE CHANGE fk_turg_prekes_kategorija fk_turg_prekes_kategorija INT NOT NULL');
        $this->addSql('ALTER TABLE TURG_PREKES_KATEGORIJA CHANGE fk_vartotojas fk_vartotojas INT NOT NULL, CHANGE fk_pardavimo_tipas fk_pardavimo_tipas INT NOT NULL');
        $this->addSql('ALTER TABLE VARTOTOJAS CHANGE role role JSON NOT NULL');
        $this->addSql('ALTER TABLE VARTOTOJO_ATSILIEPIMAS CHANGE fk_gavejas fk_gavejas INT NOT NULL, CHANGE fk_rasytojas fk_rasytojas INT NOT NULL');
        $this->addSql('ALTER TABLE ZINUTE CHANGE fk_gavejas fk_gavejas INT NOT NULL, CHANGE fk_rasytojas fk_rasytojas INT NOT NULL');
    }
}
