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