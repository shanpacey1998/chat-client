<?php

use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\Goutte\Client;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{

    /**
     * @Then the application's kernel should use :expected environment
     *
     */
    public function kernelEnvironmentShouldBe(string $expected)
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        $environment = $kernel->getEnvironment();

        if ($expected == $environment)
        {
            return true;
        }

        return false;
    }

    /**
     *
     * @Given /^the user "([^"]*)" with password "([^"]*)" and username "([^"]*)" does not exist$/
     */
    public function theUserDoesNotExist($email, $password, $username)
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $em->createQuery("DELETE FROM user WHERE username = 'test123' ");

//        $user2 = new User();
//        $user2->setEmail($email);
//        $user2->setPassword($password);
//        $user2->setUsername($username);
//        $em->remove($user2);
//        $em->flush();

    }

//    /**
//     * @Given /^the admin user "([^"]*)" with password "([^"]*)" and username "([^"]*)" does not exist$/
//     */
//    public function theAdminUserDoesNotAlreadyExist($email, $password, $username)
//    {
//        $container = $this->bootstrapSymfony();
//
//        $user = new User();
//        $user->setEmail($email);
//        $user->setPassword($password);
//        $user->setUsername($username);
//        $user->setRoles(array('ROLE_ADMIN'));
//
//        $em = $container->get('doctrine')->getManager();
//        $em->persist($user);
//        $em->flush();
//    }

    public function bootstrapSymfony()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();

    }

    /**
     * @BeforeSuite
     */
    public static function clearData()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager();
        $em->createQuery("DELETE FROM chat_client_test.user WHERE username = 'test123' ");
        $em->createQuery("DELETE FROM user WHERE username = 'test123' ");

        $user = new User();
        $user->setEmail('test@123.com');
        $user->setPassword('test123');
        $user->setUsername('test123');

        $em->remove($user);
        $em->flush();
    }

}
