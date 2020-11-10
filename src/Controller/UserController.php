<?php


namespace App\Controller;

use App\Entity\User;
use App\FileUploader;
use App\Repository\UserProfileRepository;
use App\Repository\UserProfileRepository as profileRepo;
use App\Entity\UserProfile;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile(Request $request, FileUploader $fileUploader)
    {

        $email = $this->getUser()->getEmail();
        $username = $this->getUser()->getUsername();
        $user = $this->getUser();

        $userProfile = new UserProfile();

        $form = $this->createForm(ProfileFormType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $existingProfile = $this->getUser()->getUserProfile();

            if ($uploadedFile && $existingProfile == null )
            {
                $newFilename = $fileUploader->uploadImage($uploadedFile);

                $userProfile->setImageFilename($newFilename);
                $userProfile->setUser($user);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userProfile);
                $entityManager->flush();
            }
            elseif ($uploadedFile && $existingProfile != null)
            {
                $em = $this->getDoctrine()->getManager();

                $newFile = $fileUploader->uploadImage($uploadedFile);
                $existingProfile->setImageFilename($newFile);
                $existingProfile->setUser($user);

                $em->persist($existingProfile);
                $em->flush();

            }
        }

        return $this->render('user/profile.html.twig', [
            'profileForm' => $form->createView(),
            'username' => $username,
            'email' => $email,
        ]);
    }
}