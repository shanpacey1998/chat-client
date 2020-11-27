<?php

declare(strict_types=1);

use App\Entity\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * @Then the application's kernel should use :expected environment
     *
     * @param string $expected
     *
     * @return bool
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
     *
     * @param string $email
     * @param string $password
     * @param string $username
     */
    public function theUserDoesNotExist(string $email, string $password, string $username): void
    {
        $kernel = new \App\Kernel('dev', true);
        $kernel->boot();
        $em = $kernel->getContainer()->get('doctrine')->getManager()->getConnection();
        $em->query("DELETE FROM user WHERE username = 'test123' ");
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
    }
}
