<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201105132521 extends AbstractMigration
{
<<<<<<< HEAD
    public function getDescription(): string
=======
    public function getDescription() : string
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    {
        return '';
    }

<<<<<<< HEAD
    public function up(Schema $schema): void
=======
    public function up(Schema $schema) : void
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

<<<<<<< HEAD
    public function down(Schema $schema): void
=======
    public function down(Schema $schema) : void
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_profile');
    }
}
