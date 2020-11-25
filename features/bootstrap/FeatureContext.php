<?php
declare(strict_type=1);

use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    private $passwordEncoder;

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

    public function bootstrapSymfony()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();

    }

    public function clearData()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();
        $kernel->getContainer();

        $em = $kernel->getContainer()->get('doctrine')->getManager();


        $em->createQuery("DELETE FROM chat_client_test.user WHERE username = 'test123' ");
        $em->createQuery("DELETE FROM chat_client.user WHERE email = 'test@123.com' ");
    }

}
