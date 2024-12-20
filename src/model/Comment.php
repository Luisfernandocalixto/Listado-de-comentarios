<?php

namespace App\Comentarios\model;

use PDO;
use App\Comentarios\model\Database;

class Comment extends Database
{

    private string $uuid;
    private string $date;
    public function __construct(private string $username, private string $text, private string $url)
    {
        parent::__construct();
        $this->uuid = uniqid();
    }

    public function save()
    {
        $query = $this->connect()->prepare("INSERT INTO comments (uuid, username,text, url, date) VALUES(:uuid, :username, :text, :url, NOW());");

        $query->execute(['uuid' => $this->uuid, 'username' => $this->username, 'text' => $this->text, 'url' => $this->url]);
    }

    public static function getAll($url)
    {
        $db = new Database();
        $query = $db->connect()->prepare("SELECT * FROM comments WHERE  url = :url");
        $query->execute(['url' => $url]);
        $comments = [];

        while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
            $comment = Comment::createFromArray($r);
            array_push($comments, $comment);
        }

        return $comments;
    }


    public static function createFromArray($arr)
    {
        $comment = new Comment($arr['username'], $arr['text'], $arr['url']);
        $comment->setUUID($arr['uuid']);
        $comment->setDate($arr['date']);
        return $comment;
    }

    public function setUUID($value)
    {
        $this->uuid = $value;
    }
    public function setDate($value)
    {
        $this->date = $value;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getText()
    {
        return $this->text;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function getDate()
    {
        return $this->date;
    }
}
