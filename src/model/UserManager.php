<?php
namespace App\model;

use Blogue\Manager;

class UserManager extends Manager{

    public function checkMail(string $mail)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM user WHERE mail = :mail');
         $req->execute(array(
            'mail' => $mail,
        ));
        $response = $req->fetch();
        return $response;
       
    }

    public function checkUserName(string $userName)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('SELECT * FROM user WHERE user_Name = :user_Name');
         $req->execute(array(
            'user_Name' => $userName,
        ));
        $response = $req->fetch();
        return $response;
       
    }

    public function newUser(string $userName, string $mail, string $mdp)
    {
        $bdd = $this->dbConnect();
        $req = $bdd->prepare('INSERT INTO user (user_Name, mail, jour, pass) VALUES(:user_Name, :mail, NOW(), :pass)');
        $req->execute(array(
            'user_Name' => $userName,
            'mail' => $mail,
            'pass' => $mdp,
        ));
       
    }
}