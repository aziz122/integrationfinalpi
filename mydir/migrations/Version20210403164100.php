<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210403164100 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos_siteh DROP FOREIGN KEY FK_E1FFABD3FF203408');
        $this->addSql('DROP TABLE photos_siteh');
        $this->addSql('DROP TABLE siteh');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photos_siteh (photos_id INT NOT NULL, siteh_id INT NOT NULL, INDEX IDX_E1FFABD3301EC62 (photos_id), INDEX IDX_E1FFABD3FF203408 (siteh_id), PRIMARY KEY(photos_id, siteh_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE siteh (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, place VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE photos_siteh ADD CONSTRAINT FK_E1FFABD3301EC62 FOREIGN KEY (photos_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_siteh ADD CONSTRAINT FK_E1FFABD3FF203408 FOREIGN KEY (siteh_id) REFERENCES siteh (id) ON DELETE CASCADE');
    }
}
