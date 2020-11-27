<?php

declare(strict_type=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->truncateEntities([User::class]);
    }

    public function testItLogsInUser()
    {
        $this->bootstrapSymfony();

        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123', 'agree terms' => true]);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
    }

    public function testItRegistersUser()
    {
        $this->bootstrapSymfony();
        
        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123', 'repeat password' => '123', 'username' => '123', 'agree terms' => true]);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertTrue(true);
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

    /**
     * @return ContainerInterface
     */
    public function bootstrapSymfony(): ContainerInterface
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();
    }
}
