<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108095445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_ter ADD author_id INT NOT NULL, ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE projet_ter ADD CONSTRAINT FK_DFE72F6DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DFE72F6DF675F31B ON projet_ter (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_ter DROP FOREIGN KEY FK_DFE72F6DF675F31B');
        $this->addSql('DROP INDEX IDX_DFE72F6DF675F31B ON projet_ter');
        $this->addSql('ALTER TABLE projet_ter DROP author_id, DROP date');
    }
}
