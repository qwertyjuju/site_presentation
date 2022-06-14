<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614135155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, userid_id INT NOT NULL, etat VARCHAR(255) DEFAULT NULL, INDEX IDX_24CC0DF258E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_prestation (panier_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_351DB638F77D927C (panier_id), INDEX IDX_351DB6389E45C554 (prestation_id), PRIMARY KEY(panier_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF258E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_prestation ADD CONSTRAINT FK_351DB638F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_prestation ADD CONSTRAINT FK_351DB6389E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_prestation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_prestation DROP FOREIGN KEY FK_351DB638F77D927C');
        $this->addSql('CREATE TABLE user_prestation (user_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_F6AF49419E45C554 (prestation_id), INDEX IDX_F6AF4941A76ED395 (user_id), PRIMARY KEY(user_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_prestation ADD CONSTRAINT FK_F6AF49419E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_prestation ADD CONSTRAINT FK_F6AF4941A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_prestation');
    }
}
