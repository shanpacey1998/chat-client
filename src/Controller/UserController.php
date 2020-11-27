<?php

declare(strict_type=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ProfileFormType;
use App\Form\UserFormType;
use App\Service\FileUploader;
use App\Service\FirebaseConfig;
use Doctrine\Persistence\ObjectManager;
use Kreait\Firebase\Exception\DatabaseException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/home", name="app_homepage")
     *
     * @return Response|null
     * @IsGranted("ROLE_USER")
     */
    public function homepage(): ?Response
    {
        $em = $this->getEntityManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/homepage.html.twig', [
            'contacts' => $users,
        ]);
    }

    /**
     * @Route("/messages/{user}", name="message_user")
     * @IsGranted("ROLE_USER")
     *
     * @param FirebaseConfig $firebaseConfig
     * @param Request $request
     * @param string $user
     *
     * @return Response|null
     *
     * @throws DatabaseException
     */
    public function viewUser(FirebaseConfig $firebaseConfig, Request $request, string $user): ?Response
    {
        // $user is the contact.username selected in homepage
        $currentUser = $this->getUser()->getUsername();
        $messages = $firebaseConfig->getMessages($currentUser, $user);

        $form = $this->createForm(UserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['attachment']->getData() && null != $form['messageInput']->getData()) {
                $input = $form['messageInput']->getData();
                $firebaseConfig->setMessage($currentUser, $user, $input);

                /** @var UploadedFile $uploadedFile */
                $uploadedFile = file_get_contents($form['attachment']->getData());
                $filename = $_FILES['user_form']['name']['attachment'];
                $firebaseConfig->uploadFile($uploadedFile);
                $url = $firebaseConfig->storageFileUrl($filename);
                $firebaseConfig->setMessage($currentUser, $user, $url);
            } elseif (null != $form['messageInput']->getData()) {
                $input = $form['messageInput']->getData();
                $firebaseConfig->setMessage($currentUser, $user, $input);
            } elseif (null != $form['attachment']->getData()) {
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = file_get_contents($form['attachment']->getData());
                $filename = $_FILES['user_form']['name']['attachment'];
                $firebaseConfig->uploadFile($uploadedFile);
                $url = $firebaseConfig->storageFileUrl($filename);
                $firebaseConfig->setMessage($currentUser, $user, $url);
            }
        }

        $files = $firebaseConfig->getFiles();

        if ($request->isMethod('GET')) {
            // set read to true
            $firebaseConfig->setMessage($currentUser, $user);
        }

        return $this->render('user/messages.html.twig', [
            'messages' => $messages,
            'selectedUsername' => $user,
            'files' => $files,
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param FirebaseConfig $firebaseConfig
     *
     * @return Response|null
     *
     * @throws DatabaseException
     */
    public function profile(Request $request, FileUploader $fileUploader, FirebaseConfig $firebaseConfig): ?Response
    {
        $em = $this->getEntityManager();
        $users = \count($em->getRepository(User::class)->findAll());

        $user = $this->getUser();
        $email = $this->getUser()->getEmail();
        $username = $this->getUser()->getUsername();

        $messageCount = \count($firebaseConfig->getAllMessages());

        $userProfile = new UserProfile();

        $form = $this->createForm(ProfileFormType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $existingProfile = $this->getUser()->getUserProfile();

            if ($uploadedFile != null && null == $existingProfile) {
                $newFilename = $fileUploader->uploadImage($uploadedFile);

                $userProfile->setImageFilename($newFilename);
                $userProfile->setUser($user);

                $entityManager = $this->getEntityManager();
                $entityManager->persist($userProfile);
                $entityManager->flush();
            } elseif ($uploadedFile != null && null != $existingProfile) {
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
            'msgCount' => $messageCount,
        ]);
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager(): ObjectManager
    {
        return $em = $this->getDoctrine()->getManager();
    }
}
