<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180203235954 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1DE95C867');
        $this->addSql('DROP INDEX UNIQ_64C19C1DE95C867 ON category');
        $this->addSql('ALTER TABLE category DROP sector_id');
        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E812469DE2');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category ADD sector_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1DE95C867 ON category (sector_id)');
        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E812469DE2');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }
}
