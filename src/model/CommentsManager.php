<?php 
namespace App\model;

use PDO;
use Blogue\Manager;

class CommentsManager extends Manager{

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
        $response = $bdd->query('SELECT user_Name, content FROM comments');
        $array = $response->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

  

    public function deleteComment( $id)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare("DELETE FROM posts  WHERE id=:id");
        $req->execute(array(
            'id'=> $id
        ));
    }
}