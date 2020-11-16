<?php


namespace App\Service;

use Kreait\Firebase\Database;

class FirebaseConfig
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getMessages($user1, $user2)
    {
        $messages = $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1."/".$user2)->getValue();

       return $messages;
    }

    public function setMessage($input, $user1, $user2)
    {
        $newPostKey1 = $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1)->push()->getKey();
        $newPostKey2 = $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user2)->push()->getKey();

        $update1 = [
            $user1.'/'.$newPostKey2 => $input

        ];
        $update2 = [
            $user2.'/'.$newPostKey1 => $input

        ];

        $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1)
            ->update($update2);
        $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user2)
            ->update($update1);
    }
}