<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180203115309 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, sector_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_64C19C1DE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE circle (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, count_layer INT NOT NULL, INDEX IDX_D4B76579A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_label (id INT AUTO_INCREMENT NOT NULL, sector_id INT DEFAULT NULL, angle DOUBLE PRECISION NOT NULL, radius DOUBLE PRECISION NOT NULL, INDEX IDX_6BB7F33FDE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, circle_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4BA3D9E812469DE2 (category_id), INDEX IDX_4BA3D9E870EE2FF6 (circle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE circle ADD CONSTRAINT FK_D4B76579A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE note_label ADD CONSTRAINT FK_6BB7F33FDE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E870EE2FF6 FOREIGN KEY (circle_id) REFERENCES circle (id)');
        $this->addSql('ALTER TABLE note ADD user_id INT DEFAULT NULL, ADD note_label_id INT DEFAULT NULL, ADD category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1423E2F3A7 FOREIGN KEY (note_label_id) REFERENCES note_label (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFBDFA1423E2F3A7 ON note (note_label_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E812469DE2');
        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E870EE2FF6');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1423E2F3A7');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1DE95C867');
        $this->addSql('ALTER TABLE note_label DROP FOREIGN KEY FK_6BB7F33FDE95C867');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE circle');
        $this->addSql('DROP TABLE note_label');
        $this->addSql('DROP TABLE sector');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('DROP INDEX IDX_CFBDFA14A76ED395 ON note');
        $this->addSql('DROP INDEX UNIQ_CFBDFA1423E2F3A7 ON note');
        $this->addSql('ALTER TABLE note DROP user_id, DROP note_label_id, DROP category');
    }
}
