<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     * @Route("/register", name="user_register")
     *
     * @return Response
     */
    public function register()
    {

        return $this->render('user/register.twig', array('user' => new User()));
    }

    /**
     * @Route("/home", name="app_homepage")
     * @return Response
     */
    public function homepage()
    {
        return $this->render('user/homepage.html.twig', [
            'title' => 'Homepage'
        ]);
    }
}