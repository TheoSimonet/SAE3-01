<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230107153734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternance ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE alternance ADD CONSTRAINT FK_445F37B9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_445F37B9F675F31B ON alternance (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternance DROP FOREIGN KEY FK_445F37B9F675F31B');
        $this->addSql('DROP INDEX IDX_445F37B9F675F31B ON alternance');
        $this->addSql('ALTER TABLE alternance DROP author_id');
    }
}
