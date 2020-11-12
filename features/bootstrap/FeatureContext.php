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
    private $passwordEncoder;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     */
    public function __construct()
    {

    }

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
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setUsername($username);

        $em = $this->bootstrapSymfony()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

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
     *
     */
    public function clearData()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $kernel->getContainer();

        $em = $kernel->getContainer()->get('doctrine')->getManager();

//        //$users = $em->getRepository('App:User')->findAll();
//
        $em->createQuery("DELETE FROM chat_client_test.user WHERE username = 'test123' ");
        $em->createQuery("DELETE FROM chat_client.user WHERE email = 'test@123.com' ");
        //$em->remove($users);
    }

}
