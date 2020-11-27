<?php
declare(strict_type=1);

namespace App\Tests\Controller;

<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
>>>>>>> 71f797b15e7fdb850efa62e3858e3d139e2cdd4e
        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123']);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======



>>>>>>> daf90324689f116017e1e50a3d230c376734f133
>>>>>>> 71f797b15e7fdb850efa62e3858e3d139e2cdd4e
    }

    public function testItRegistersUser()
    {
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
>>>>>>> 71f797b15e7fdb850efa62e3858e3d139e2cdd4e
        $this->client->request('POST', '/', ['email' => '123@123.com', 'password' => '123', 'username' => '123']);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $this->assertTrue(true);
    }

<<<<<<< HEAD
    public function truncateEntities(array $entities)
=======
    public function truncateEntities(Array $entities)
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    {
        $connection = $this->getEntityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

<<<<<<< HEAD
        if ($dbPlatform->supportsForeignKeyConstraints()) {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity) {
=======
        if ($dbPlatform->supportsForeignKeyConstraints())
        {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
        }

        foreach ($entities as $entity)
        {
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
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
<<<<<<< HEAD
}
=======
}
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
