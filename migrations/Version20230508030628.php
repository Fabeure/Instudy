<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508030628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CEE1E31ED');
        $this->addSql('DROP INDEX UNIQ_FDCA8C9CEE1E31ED ON cours');
        $this->addSql('ALTER TABLE cours CHANGE teach_id teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C41807E1D ON cours (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');
        $this->addSql('DROP INDEX IDX_FDCA8C9C41807E1D ON cours');
        $this->addSql('ALTER TABLE cours CHANGE teacher_id teach_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CEE1E31ED FOREIGN KEY (teach_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FDCA8C9CEE1E31ED ON cours (teach_id)');
    }
}
