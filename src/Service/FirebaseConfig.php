<?php

declare(strict_type=1);

namespace App\Service;

use Google\Cloud\Storage\ObjectIterator;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FirebaseConfig
{
    /**
     * @param string $user1
     * @param string $user2
     *
     * @return mixed
     *
     * @throws DatabaseException
     */
    public function getMessages(string $user1, string $user2)
    {
        $sortUsers = [$user1, $user2];
        rsort($sortUsers);
        $sortedUsers = implode('-', $sortUsers); // sorts the users so that the same result is given for both

        $db = $this->getDatabase();

        return $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/' . $sortedUsers)->getValue();
    }

    /**
     * @return mixed
     *
     * @throws DatabaseException
     */
    public function getAllMessages()
    {
        $db = $this->getDatabase();

        return $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/')->getValue();
    }

    /**
     * @param string $user1
     * @param string $user2
     * @param string|null $input
     *
     * @throws DatabaseException
     */
    public function setMessage(string $user1, string $user2, $input = null): void
    {
        $sortUsers = [$user1, $user2];
        rsort($sortUsers);
        $sortedUsers = implode('-', $sortUsers);

        $db = $this->getDatabase();
        $messages = $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/' . $sortedUsers)->getValue();

        if (null != $input) {
            $key = $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/' . $user1)->push()->getKey(); //get new post key for the message

            $update = [
                '/' . $key => [
                    'message' => $input,
                    'sent_at' => date('H:i'),
                    'read' => false,
                    'sent_by' => $user1,
                ],
            ];

            $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/' . $sortedUsers)->update($update);
        } elseif (null != $messages) {
            // loop through messages as keys, if that message is unread, update to read
            foreach ($messages as $messageKey => $message) {
                if (false == $message['read'] && $message['sent_by'] != $user1) {
                    $update = [
                        '/' . $messageKey => [
                            'message' => $message['message'],
                            'sent_at' => $message['sent_at'],
                            'read' => true,
                            'sent_by' => $message['sent_by'],
                        ],
                    ];
                    $db->getReferenceFromUrl('https://chat-client-464de.firebaseio.com/messages/' . $sortedUsers)->update($update);
                }
            }
        }
    }

    /**
     * @param String $file
     */
    public function uploadFile(String $file): void
    {
        $storage = $this->getStorage();
        $bucket = $storage->getBucket();
        $bucket->upload($file,
            [
                'name' => $_FILES['user_form']['name']['attachment'],
            ]);
    }

    /**
     * @return ObjectIterator
     */
    public function getFiles()
    {
        $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'] . '/chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $storage = $factory->createStorage();
        return $storage->getBucket()->objects();
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        $factory = (new Factory())->withServiceAccount($_SERVER['DOCUMENT_ROOT'] . '/chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');

        return $factory->createDatabase();
    }

    /**
     * @return Storage
     */
    public function getStorage()
    {
        $factory = (new Factory())->withServiceAccount($_SERVER['DOCUMENT_ROOT'] . '/chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');

        return $factory->createStorage();
    }

    /**
     * @param string $name
     * @param array $path
     *
     * @return string
     */
    public function storageFileUrl(string $name, $path = [])
    {
        $base = 'https://firebasestorage.googleapis.com/v0/b/';
        $db = 'chat-client-464de.appspot.com/o/';

        $url = $base . $db;
        if (\count($path) > 0) {
            $url .= implode('%2F', $path) . '%2F';
        }

        return $url . $name . '?alt=media';
    }
}

