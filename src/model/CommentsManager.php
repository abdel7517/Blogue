<?php

namespace App\model;

use PDO;
use Blogue\Manager;

class CommentsManager extends Manager
{

    public function addComment($id_Post, $user_Name, $content)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO comments (id_Post, user_Name, content) 
        VALUES(:id_Post, :user_Name, :content)');
        $req->execute(array(
            'id_Post' => $id_Post,
            'user_Name' => $user_Name,
            'content' => $content
        ));
    }



    public function getComments()
    {
        $bdd = $this->dbConnect();
        $response = $bdd->query('SELECT * FROM comments ORDER BY day DESC');
        $array = $response->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }



    public function deleteComment($id)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare("DELETE FROM comments  WHERE id=:id");
        $req->execute(array(
            'id' => $id
        ));
    }

    public function addReport(string $arraySerialized, int $idComment)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('UPDATE comments SET reports = :report WHERE id = :id ');
        $req->execute(array(
            'report' => $arraySerialized,
            'id'=> $idComment
        ));
    }

    public function getReport(int $id_Comment, int $id_Post )
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT reports FROM comments WHERE 
        id = :id_Comment AND id_Post = :id_Post ');
        $req->execute(array(
            'id_Comment'=> $id_Comment,
            'id_Post' => $id_Post
        ));
        $array = $req->fetchAll(PDO::FETCH_ASSOC);
        return $array[0]['reports'];
    }

}
