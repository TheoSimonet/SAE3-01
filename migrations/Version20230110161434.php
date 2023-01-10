<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110161434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alternance DROP num_alternance');
        $this->addSql('ALTER TABLE candidature ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projet_ter DROP nump_proj');
        $this->addSql('ALTER TABLE stage CHANGE num_stage author_id INT NOT NULL');
        $this->addSql('ALTER TABLE stage ADD CONSTRAINT FK_C27C9369F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C27C9369F675F31B ON stage (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stage DROP FOREIGN KEY FK_C27C9369F675F31B');
        $this->addSql('DROP INDEX IDX_C27C9369F675F31B ON stage');
        $this->addSql('ALTER TABLE stage CHANGE author_id num_stage INT NOT NULL');
        $this->addSql('ALTER TABLE candidature DROP date');
        $this->addSql('ALTER TABLE alternance ADD num_alternance VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE projet_ter ADD nump_proj INT NOT NULL');
    }
}
