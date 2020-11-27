<?php

declare(strict_type=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {

    }

    public function testItLogsInUser()
    {
       // TODO
    }

    public function testItRegistersUser()
    {
        //TODO
    }

    public function truncateEntities(array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        if ($dbPlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity) {
            $query = $dbPlatform->getTruncateTableSQL(
                $this->getEntityManager()->getClassMetadata($entity)->getTableName()
            );

            $connection->executeUpdate($query);
        }
        if ($dbPlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function getEntityManager()
    {
        $kernel = new Kernel('test', true);

        return $em = $kernel->getContainer()->get('doctrine')->getManager();
    }
}
