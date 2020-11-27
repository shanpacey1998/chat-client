<?php


namespace App\Controller;

use App\Entity\User;
use App\Service\FileUploader;
use App\Repository\UserProfileRepository;
use App\Repository\UserProfileRepository as profileRepo;
use App\Entity\UserProfile;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use App\Service\FirebaseConfig;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function homepage(FirebaseConfig $firebaseConfig)
    {
        $em = $this->getEntityManager();
        $users = $em->getRepository('App:User')->findAll();

        $currentUser = $this->getUser()->getUsername();

        $messages = $firebaseConfig->getMessages($currentUser);


        return $this->render('user/homepage.html.twig', [
            'contacts' => $users,
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/messages", name="message_user")
     */
    public function viewUser(FirebaseConfig $firebaseConfig, Request $request)
    {
        $em = $this->getEntityManager();
        $users = $em->getRepository('App:User')->findAll();


        $currentUser = $this->getUser()->getUsername();
        $messages = $firebaseConfig->getMessages($currentUser);

        $input = $request->request->get('message_input');

        $firebaseConfig->setMessage($input,$currentUser, '124');

        return $this->render('user/messages.html.twig', [
            'messages' => $messages
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, FileUploader $fileUploader)
    {
        $em = $this->getEntityManager();
        $users = count($em->getRepository('App:User')->findAll());

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

                $entityManager = $this->getEntityManager();
                $entityManager->persist($userProfile);
                $entityManager->flush();
            }
            elseif ($uploadedFile && $existingProfile != null)
            {
                $em = $this->getEntityManager();

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
            'contacts' => $users
        ]);
    }

    public function getEntityManager()
    {
        return $em = $this->getDoctrine()->getManager();
    }
}