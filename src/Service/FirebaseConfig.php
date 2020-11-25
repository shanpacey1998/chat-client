<?php


namespace App\Service;


use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;

class FirebaseConfig
{
    public function getMessages($user1, $user2)
    {
        $sortUsers = [$user1, $user2];
        rsort($sortUsers);
        $sortedUsers = implode('-', $sortUsers); // sorts the users so that the same result is given for both

        $db = $this->getDatabase();
        return $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$sortedUsers)->getValue();
    }

    public function getAllMessages()
    {
        $db = $this->getDatabase();
        return $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/")->getValue();
    }

    public function setMessage($user1, $user2, $input = null)
    {
        $sortUsers = [$user1, $user2];
        rsort($sortUsers);
        $sortedUsers = implode('-', $sortUsers);

        $db = $this->getDatabase();
        $messages = $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$sortedUsers)->getValue();

        if ($input != null)
        {
            $key = $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1)->push()->getKey(); //get new post key for the message

            $update = [
                '/' . $key => [
                    'message' => $input,
                    'sent_at' => date("H:i"),
                    'read' => false,
                    'sent_by' => $user1
                ]

            ];

            $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$sortedUsers)->update($update);
        }
        elseif ($input == null && $messages != null)
        {
            // what it needs to do: loop through messages as keys, if that message is unread, update that message's key to read
            foreach ($messages as $messageKey => $message)
            {

                if ($message['read'] == false && $message['sent_by'] != $user1)
                {

                    $update = [
                        '/' . $messageKey => [
                            'message' => $message['message'],
                            'sent_at' => $message['sent_at'],
                            'read' => true,
                            'sent_by' => $message['sent_by']
                        ]

                    ];
                    $db->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$sortedUsers)->update($update);
                }

            }
        }

    }


    public function uploadFile($file)
    {
        $storage = $this->getStorage();
        $bucket = $storage->getBucket();
        $bucket->upload($file,
            [
                'name' => $_FILES['user_form']['name']['attachment'],
            ]);

    }

    public function getFiles()
    {
        $storage = $this->getStorage();
        $bucket = $storage->getBucket()->objects();
        return $bucket;

    }

    public function getDatabase()
    {
        $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $db = $factory->createDatabase();

        return $db;
    }

    public function getStorage()
    {
        $factory = (new Factory)->withServiceAccount($_SERVER['DOCUMENT_ROOT'].'/chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $storage = $factory->createStorage();

        return $storage;
    }

    function storageFileUrl($name, $path = []) {
        $base = 'https://firebasestorage.googleapis.com/v0/b/';
        $db = 'chat-client-464de.appspot.com/o/';

        $url = $base.$db;
        if(sizeof($path) > 0) {
            $url .= implode('%2F', $path).'%2F';
        }
        $link = $url.$name.'?alt=media';
        return $link;
    }
}