<?php

use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\Goutte\Client;
use Behat\MinkExtension\Context\RawMinkContext;

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
     */
    public function __construct()
    {
    }

    public function bootstrapSymfony()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();
    }

    /**
     * @Then the application's kernel should use :expected environment
     */
    public function kernelEnvironmentShouldBe(string $expected)
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        $environment = $kernel->getEnvironment();

        if ($expected == $environment) {
            return true;
        }

        return false;
    }

    /**
     * @Given /^the user "([^"]*)" with password "([^"]*)" and username "([^"]*)" does not exist$/
     */
    public function theUserDoesNotExist($email, $password, $username)
    {
        $container = $this->bootstrapSymfony();

//        $em = $container->get('doctrine')->getManager();
//        $em->createQuery("DELETE FROM chat_client_test.user WHERE username = 'test123';
//        DELETE FROM chat_client.user WHERE username = 'test123' ")->execute();

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setUsername($username);

        $em = $container->get('doctrine')->getManager();
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

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $em = $this->bootstrapSymfony()->get('doctrine')->getManager();

        $em->createQuery('DELETE FROM chat_client_test.user WHERE id >=0')->execute();
    }
}
