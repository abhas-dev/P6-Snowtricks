<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211115155745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ADD main_trick_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91ED67AEA98 FOREIGN KEY (main_trick_image_id) REFERENCES trick_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91ED67AEA98 ON trick (main_trick_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91ED67AEA98');
        $this->addSql('DROP INDEX UNIQ_D8F0A91ED67AEA98 ON trick');
        $this->addSql('ALTER TABLE trick DROP main_trick_image_id');
    }
}
