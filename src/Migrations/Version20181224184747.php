<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181224184747 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domain_application (domain_id INT NOT NULL, application_id INT NOT NULL, INDEX IDX_A27CEAC2115F0EE5 (domain_id), INDEX IDX_A27CEAC23E030ACD (application_id), PRIMARY KEY(domain_id, application_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domain_application ADD CONSTRAINT FK_A27CEAC2115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domain_application ADD CONSTRAINT FK_A27CEAC23E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE domain_application DROP FOREIGN KEY FK_A27CEAC23E030ACD');
        $this->addSql('ALTER TABLE domain_application DROP FOREIGN KEY FK_A27CEAC2115F0EE5');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE domain');
        $this->addSql('DROP TABLE domain_application');
    }
}
