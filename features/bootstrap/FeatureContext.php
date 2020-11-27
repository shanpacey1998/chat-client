<?php
declare(strict_type=1);

declare(strict_types=1);

use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
<<<<<<< HEAD
use Symfony\Component\DependencyInjection\ContainerInterface;
=======
>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
<<<<<<< HEAD
    /**
     * @Then the application's kernel should use :expected environment
     *
     * @param string $expected
     *
     * @return bool
=======
    private $passwordEncoder;


    /* @Then the application's kernel should use :expected environment
>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528
     */
    public function kernelEnvironmentShouldBe(string $expected)
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        $environment = $kernel->getEnvironment();

<<<<<<< HEAD
=======

>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528
        if ($expected == $environment) {
            return true;
        }
        return false;
    }

    /**
     * @Given /^the user "([^"]*)" with password "([^"]*)" and username "([^"]*)" does not exist$/
     *
     * @param string $email
     * @param string $password
     * @param string $username
     */
    public function theUserDoesNotExist(string $email, string $password, string $username): void
    {
<<<<<<< HEAD
        $kernel = new \App\Kernel('dev', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager()->getConnection();
        $em->query("DELETE FROM user WHERE username = 'test123' ");
    }

    /**
     * @return ContainerInterface
     */
    public function bootstrapSymfony(): ContainerInterface
=======
        $container = $this->bootstrapSymfony();
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setUsername($username);

        $em = $this->bootstrapSymfony()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
    }

    public function bootstrapSymfony()
>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();
    }

<<<<<<< HEAD
    /**
     * @BeforeSuite
     */
    public static function clearData(): void
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager()->getConnection();
        $em->query("DELETE FROM chat_client_test.user WHERE username = 'test123' ");
        $em->query("DELETE FROM user WHERE username = 'test123' ");
=======
    public function clearData()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $kernel->getContainer();

        $em = $kernel->getContainer()->get('doctrine')->getManager();


        $em->createQuery("DELETE FROM chat_client_test.user WHERE username = 'test123' ");
        $em->createQuery("DELETE FROM chat_client.user WHERE email = 'test@123.com' ");
>>>>>>> a925a5dd926723b2d1f86a7925cd3833f1717528
    }
}


