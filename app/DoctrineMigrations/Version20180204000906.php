<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180204000906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_label ADD note_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note_label ADD CONSTRAINT FK_6BB7F33F26ED0855 FOREIGN KEY (note_id) REFERENCES note (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6BB7F33F26ED0855 ON note_label (note_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_label DROP FOREIGN KEY FK_6BB7F33F26ED0855');
        $this->addSql('DROP INDEX UNIQ_6BB7F33F26ED0855 ON note_label');
        $this->addSql('ALTER TABLE note_label DROP note_id');
    }
}
