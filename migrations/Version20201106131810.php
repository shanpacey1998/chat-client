<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201106131810 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user_profile DROP username, DROP email');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB405A76ED395 ON user_profile (user_id)');
    }

<<<<<<< HEAD
    public function down(Schema $schema): void
=======
    public function down(Schema $schema) : void
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP INDEX UNIQ_D95AB405A76ED395 ON user_profile');
        $this->addSql('ALTER TABLE user_profile ADD username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
