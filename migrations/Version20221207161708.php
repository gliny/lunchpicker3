<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207161708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_lunch (user_id INT NOT NULL, lunch_id INT NOT NULL, INDEX IDX_6B33F183A76ED395 (user_id), INDEX IDX_6B33F183D7C83568 (lunch_id), PRIMARY KEY(user_id, lunch_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_lunch ADD CONSTRAINT FK_6B33F183A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_lunch ADD CONSTRAINT FK_6B33F183D7C83568 FOREIGN KEY (lunch_id) REFERENCES lunch (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_lunch DROP FOREIGN KEY FK_6B33F183A76ED395');
        $this->addSql('ALTER TABLE user_lunch DROP FOREIGN KEY FK_6B33F183D7C83568');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_lunch');
    }
}
