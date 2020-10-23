<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/home", name="app_homepage")
     * @return Response
     */
    public function homepage()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('user/homepage.html.twig', [
            'title' => 'Homepage'
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile()
    {
        $username = $this->getUser()->getUsername();
        $email = $this->getUser()->getEmail();


        return $this->render('user/profile.html.twig', [
            'title' => 'Profile',
            'username' => $username,
            'email' => $email
        ]);
    }
}