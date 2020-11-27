<?php

use App\Entity\User;
use Behat\Behat\Context\Context;
<<<<<<< HEAD
use Behat\Mink\Driver\Goutte\Client;
use Behat\MinkExtension\Context\RawMinkContext;
=======
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\Goutte\Client;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
>>>>>>> daf90324689f116017e1e50a3d230c376734f133

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
<<<<<<< HEAD
     */
    public function __construct()
    {
    }

=======
     *
     */
    public function __construct()
    {

    }

    /**
     *
     */
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    public function bootstrapSymfony()
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        return $kernel->getContainer();
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
    }

    /**
     * @Then the application's kernel should use :expected environment
<<<<<<< HEAD
=======
     *
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
     */
    public function kernelEnvironmentShouldBe(string $expected)
    {
        $kernel = new \App\Kernel('test', true);
        $kernel->boot();

        $environment = $kernel->getEnvironment();

<<<<<<< HEAD
        if ($expected == $environment) {
=======
        if ($expected == $environment)
        {
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
            return true;
        }

        return false;
    }

    /**
<<<<<<< HEAD
=======
     *
>>>>>>> daf90324689f116017e1e50a3d230c376734f133
     * @Given /^the user "([^"]*)" with password "([^"]*)" and username "([^"]*)" does not exist$/
     */
    public function theUserDoesNotExist($email, $password, $username)
    {
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
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
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
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
<<<<<<< HEAD
=======

>>>>>>> daf90324689f116017e1e50a3d230c376734f133
}
