<?php

namespace App\Controller;

use App\model\UserManager;
use Blogue\Controller;
use Blogue\Request;

class LoginController extends Controller
{

    public function log()
    {
        //check if password match with mail 
        $user = new UserManager;
        $request = new Request;
        $errorMessage = '';
        $method = $request->getMethode();
        if ($method == "POST") {
            $mailUser = $request->get('POST', 'mail');
            $password = $request->get('POST', 'mdp');
            $userData = array();
          
            $response = $user->getData($mailUser);
            //if true register the state of user on session 
            if ($response) {
                if ($response['pass'] == $password) {
                    $errorMessage = 'Vous êtes connecté avec succés ';
                    $userData['role'] = $response['role'];
                    $userData['mail'] = $response['mail'];
                    $userData['userName'] = $response['userName'];
                    $request->newSession("user", $userData);
                } else {
                    $errorMessage = 'L\'association mot de passe, email est incorrect ';
                }
            } else {
                $errorMessage = 'L\'association mot de passe, email est incorrect ';
            }
            return $this->render('login.html.twig', ['errorMessage' => $errorMessage ]);
        }
        return $this->render('login.html.twig', ['errorMessage' => $errorMessage]);
    }

    public function register()
    {
        //check if password match with mail 
        $user = new UserManager;
        $request = new Request;
        $errorMessage = '';
        $method = $request->getMethode();
        if ($method == "POST") {
            $mailUser = $request->get('POST', 'mail');
            $password = $request->get('POST', 'mdp');
            $userName = $request->get('POST', 'userName');
            $userData = array();
            $userData['mail'] = $mailUser;
            $userData['pass'] = $password;
            $userData['userName'] = $userName;
            $response = $user->getData($mailUser);
            //if true register the state of user on session 
            if (!$response) {
                if ($password == $password) {
                    $errorMessage = 'Vous êtes connecté avec succés ';
                    $request->newSession("user", $userData);
                    $user->newUser($userName, $mailUser, $password);
                } else {
                    $errorMessage = 'Les mots de passe ne sont pas identique ';
                }
            } else {
                $errorMessage = 'Cette email est déja utilisé';
            }
            return $this->render('login.html.twig', ['errorMessage' => $errorMessage]);
        }
        return $this->render('login.html.twig', ['errorMessage' => $errorMessage]);
    }
}
