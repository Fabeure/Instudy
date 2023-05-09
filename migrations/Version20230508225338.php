<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508225338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, course_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, response VARCHAR(255) DEFAULT NULL, INDEX IDX_B6F7494EF624B39D (sender_id), INDEX IDX_B6F7494E591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E591CC992 FOREIGN KEY (course_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE cours ADD teacher_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD nom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9C41807E1D ON cours (teacher_id)');
        $this->addSql('ALTER TABLE matiere ADD teach_id INT DEFAULT NULL, ADD matiere_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AEE1E31ED FOREIGN KEY (teach_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9014574AEE1E31ED ON matiere (teach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EF624B39D');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E591CC992');
        $this->addSql('DROP TABLE question');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C41807E1D');
        $this->addSql('DROP INDEX IDX_FDCA8C9C41807E1D ON cours');
        $this->addSql('ALTER TABLE cours DROP teacher_id, DROP updated_at, DROP nom');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AEE1E31ED');
        $this->addSql('DROP INDEX UNIQ_9014574AEE1E31ED ON matiere');
        $this->addSql('ALTER TABLE matiere DROP teach_id, DROP matiere_name');
    }
}
