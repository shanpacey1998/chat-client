<?php


namespace App\Controller;

use App\Form\UserFormType;
use App\Service\FileUploader;
use App\Entity\UserProfile;
use App\Form\ProfileFormType;
use App\Service\FirebaseConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/home", name="app_homepage")
     * @return Response
     */
    public function homepage()
    {
        $em = $this->getEntityManager();
        $users = $em->getRepository('App:User')->findAll();

        return $this->render('user/homepage.html.twig', [
            'contacts' => $users
        ]);
    }

    /**
     * @Route("/messages/{user}", name="message_user")
     */
    public function viewUser(FirebaseConfig $firebaseConfig, Request $request, $user)
    {
        $currentUser = $this->getUser()->getUsername();
        $messages = $firebaseConfig->getMessages($currentUser, $user);

        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['attachment']->getData() && $form['messageInput']->getData() != null )
            {
                $input = $form['messageInput']->getData();
                $input2 = $input . " - " . $currentUser;
                $firebaseConfig->setMessage($input2, $currentUser, $user);

                /** @var UploadedFile $uploadedFile */
                $uploadedFile = file_get_contents($form['attachment']->getData());
                $filename = $_FILES['user_form']['name']['attachment'];
                $firebaseConfig->uploadFile($uploadedFile);
                $url = $firebaseConfig->storageFileUrl($filename);
                $firebaseConfig->setMessage($url, $currentUser, $user);

            }
            elseif ($form['messageInput']->getData() != null)
            {
                $input = $form['messageInput']->getData();
                $input2 = $input . " - " . $currentUser;
                $firebaseConfig->setMessage($input2, $currentUser, $user);
            }
            elseif ($form['attachment']->getData() != null)
            {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = file_get_contents($form['attachment']->getData());
                $filename = $_FILES['user_form']['name']['attachment'];
                $firebaseConfig->uploadFile($uploadedFile);
//                $firebaseConfig->setMessage($filename, $currentUser, $user);
                $url = $firebaseConfig->storageFileUrl($filename);
                $firebaseConfig->setMessage($url, $currentUser, $user);
            }


        }

        $files = $firebaseConfig->getFiles();

        return $this->render('user/messages.html.twig', [
            'messages' => $messages,
            'selectedUsername' => $user,
            'files' => $files,
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile(Request $request, FileUploader $fileUploader, FirebaseConfig $firebaseConfig)
    {
        $em = $this->getEntityManager();
        $users = count($em->getRepository('App:User')->findAll());

        $email = $this->getUser()->getEmail();
        $username = $this->getUser()->getUsername();
        $user = $this->getUser();

        $messageCount = count($firebaseConfig->getAllMessages($username));

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
            'contacts' => $users,
            'msgCount' => $messageCount
        ]);
    }

    public function getEntityManager()
    {
        return $em = $this->getDoctrine()->getManager();
    }
}