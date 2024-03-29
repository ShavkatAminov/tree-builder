<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Tree;
use App\Repository\TreeRepository;
use App\Services\FakeTree;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702114205 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tree (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, position INT NOT NULL, parent_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
    public function postUp(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $fakeTree = new FakeTree($em);
        $fakeTree->create();
        parent::postUp($schema); // TODO: Change the autogenerated stub
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tree');
    }
}
