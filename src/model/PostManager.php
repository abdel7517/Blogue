<?php
namespace App\model;

use Blogue\Manager;
use PDO;

class PostManager extends Manager
{
  

    public function addPost($title, $content, $intro, $reads, $img)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (title, content, day, intro, timeToRead, img) VALUES(:title, :content, NOW(), :intro, :timeToRead, :img)');
        $req->execute(array(
            'title' => $title,
            'content' => $content,
            'intro' => $intro,
            'timeToRead' => $reads,
            'img' => $img
        ));
    }

    public function getPost($idPost)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM posts WHERE id = :id');
        $req->execute(array('id'=> $idPost));
        $rep = $req->fetch();

        return $rep;
    }

    public function getPosts()
    {
        $bdd = $this->dbConnect();
        $response = $bdd->query('SELECT * FROM posts ORDER BY day desc');
        $array = $response->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function updatePost($title, $content, $id)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare("UPDATE posts SET title=:title, content=:content WHERE id=:id");
        $req->execute(array(
            'title' => $title,
            'content' => $content,
            'id'=> $id
        ));
    }

    public function deletePost( $id)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare("DELETE FROM posts  WHERE id=:id");
        $req->execute(array(
            'id'=> $id
        ));
    }
}
