<?php

namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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

        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123', 'agree terms' => true]);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();



    }

    public function testItRegistersUser()
    {

        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123', 'repeat password' => '123', 'username' => '123', 'agree terms' => true]);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertTrue(true);
    }

    public function truncateEntities(Array $entities)
    {
        $connection = $this->getEntityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        if ($dbPlatform->supportsForeignKeyConstraints())
        {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity)
        {
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
        return self::$kernel->getContainer()->get('doctrine')->getManager();
    }
}