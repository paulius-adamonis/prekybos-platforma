<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181201174209 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE klausimas CHANGE fk_klausiantysis fk_klausiantysis INT NOT NULL, CHANGE fk_klausimo_tipas fk_klausimo_tipas INT NOT NULL');
        $this->addSql('ALTER TABLE skundas CHANGE fk_busena fk_busena INT NOT NULL, CHANGE fk_skundo_tipas fk_skundo_tipas INT NOT NULL, CHANGE fk_pareiskejas fk_pareiskejas INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE KLAUSIMAS CHANGE fk_klausimo_tipas fk_klausimo_tipas INT DEFAULT NULL, CHANGE fk_klausiantysis fk_klausiantysis INT DEFAULT NULL');
        $this->addSql('ALTER TABLE SKUNDAS CHANGE fk_pareiskejas fk_pareiskejas INT DEFAULT NULL, CHANGE fk_skundo_tipas fk_skundo_tipas INT DEFAULT NULL, CHANGE fk_busena fk_busena INT DEFAULT NULL');
    }
}
