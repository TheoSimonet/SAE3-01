<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103160038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, id_stage_id INT NOT NULL, id_user_id INT NOT NULL, cv_filename VARCHAR(255) NOT NULL, INDEX IDX_E33BD3B872433D06 (id_stage_id), INDEX IDX_E33BD3B879F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B872433D06 FOREIGN KEY (id_stage_id) REFERENCES stage (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B872433D06');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B879F37AE5');
        $this->addSql('DROP TABLE candidature');
    }
}
