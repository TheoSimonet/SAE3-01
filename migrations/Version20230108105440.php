<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108105440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE selection (id INT AUTO_INCREMENT NOT NULL, id_projet_id INT NOT NULL, id_user_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_96A50CD780F43E55 (id_projet_id), INDEX IDX_96A50CD779F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD780F43E55 FOREIGN KEY (id_projet_id) REFERENCES projet_ter (id)');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD780F43E55');
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD779F37AE5');
        $this->addSql('DROP TABLE selection');
    }
}
