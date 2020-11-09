<?php
namespace App\model;

use Blogue\Manager;

class UserManager extends Manager{

    public function getData(string $mail)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM user WHERE mail = :mail');
         $req->execute(array(
            'mail' => $mail,
        ));
        $response = $req->fetch();
        return $response;
       
    }

    public function newUser(string $userName, string $mail, string $mdp)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO user (userName, mail, jour, pass) VALUES(:userName, :mail, NOW(), :pass)');
        $req->execute(array(
            'userName' => $userName,
            'mail' => $mail,
            'pass' => $mdp,
        ));
       
    }
}