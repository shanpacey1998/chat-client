<?php
declare(strict_type=1);

namespace App\Service;

use Google\Cloud\Storage\ObjectIterator;
use Kreait\Firebase\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;

class FirebaseConfig
{
    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }

    /**
     * @param $user1
     * @param $user2
     * @return mixed
     * @throws DatabaseException
     */
    public function getMessages($user1, $user2)
    {
        return $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1."/".$user2)->getValue();
    }

    /**
     * @param $user1
     * @return mixed
     * @throws DatabaseException
     */
    public function getAllMessages($user1)
    {
        return $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1)->getValue();
    }

    /**
     * @param $input
     * @param $user1
     * @param $user2
     * @throws DatabaseException
     */
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

        $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user1)->update($update2);
        $this->database->getReferenceFromUrl("https://chat-client-464de.firebaseio.com/messages/".$user2)->update($update1);
    }

    /**
     * @param $file
     */
    public function uploadFile($file)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__.'/../chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $storage = $factory->createStorage();
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
        $factory = (new Factory)->withServiceAccount(__DIR__.'/../chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $storage = $factory->createStorage();
        return $storage->getBucket()->objects();

    }

    /**
     * @param $file
     * @return string
     */
    public function getFileUrl($file)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__.'/../chat-client-464de-firebase-adminsdk-8kmje-5e3f29d65e.json');
        $storage = $factory->createStorage();
        return $storage->getBucket()->object($file)->signedUrl(time() + 1000 * 60 * 2);
    }

    /**
     * @param $name
     * @param array $path
     * @return string
     */
    function storageFileUrl($name, $path = []) {
        $base = 'https://firebasestorage.googleapis.com/v0/b/';
        $db = 'chat-client-464de.appspot.com/o/';
        $projectId = 'chat-client-464de';

        $url = $base.$db;
        if(sizeof($path) > 0) {
            $url .= implode('%2F', $path).'%2F';
        }

        return $url.$name.'?alt=media';
    }
}