<?php
namespace App\model;

use Blogue\Manager;
use PDO;

class PostManager extends Manager
{

    public function addPost($nom, $titre,  $infos, $image = 0)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO posts (nom, titre, jour, infos, photo) VALUES(:nom, :titre, NOW(), :infos, :photo)');
        $req->execute(array(
            'nom' => $nom,
            'titre' => $titre,
            'infos' => $infos,
            'photo' => $image

        ));
    }

    public function getPost($idPost)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM posts WHERE id = ?');
        $req->execute(array($idPost));
        $rep = $req->fetch();

        return $rep;
    }

    public function getPosts()
    {
        $bdd = $this->dbConnect();
        $response = $bdd->query('SELECT id, title FROM posts');
        $array = $response->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }
}
