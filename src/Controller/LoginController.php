<?php

namespace App\Controller;

use App\model\UserManager;
use Blogue\Controller;
use Blogue\Request;

class LoginController extends Controller
{
    public $request;
    public function __construct()
    {
        $this->request = new Request;
    }

    public function log()
    {
        //check if password match with mail 
        $user = new UserManager;
        $request = new Request;
        $errorMessage = '';
        $method = $request->getMethode();

        // check if the user are loged 
        $userSession = $this->request->getSession('user');
        if ($userSession != '') {
            return $this->render('user/index.html.twig', ['user' => $userSession]);
        }

        if ($method == "POST") {
            $mailUser = $request->getPost('mail');
            $password = $request->getPost('mdp');
            $hashPassword = hash("sha256", $password);
            $userData = array();
            $response = $user->checkMail($mailUser);
            
            if ($response) {
                if ($response['pass'] == $hashPassword) {
                    $errorMessage = 'Vous êtes connecté avec succés ';
                    $userData['role'] = $response['role'];
                    $userData['mail'] = $response['mail'];
                    $userData['userName'] = $response['user_Name'];
                    $request->newSession("user", $userData);
                    return $this->log();
                } else {
                    $errorMessage = 'L\'association mot de passe, email est incorrect ';
                }
            } else {
                $errorMessage = 'L\'association mot de passe, email est incorrect ';
            }
            return $this->render('user/login.html.twig', ['errorMessage' => $errorMessage]);
        }

        return $this->render('user/login.html.twig', ['errorMessage' => $errorMessage]);
    }

    public function disconnect()
    {
        $this->request->sessionDestroy();
        $this->log();
    }

    public function register()
    {
        //check if password match with mail 
        $user = new UserManager;
        $request = new Request;
        $errorMessage = '';
        $method = $request->getMethode();
        if ($method == "POST") {
            $mailUser = $request->getPost('mail');
            $password = $request->getPost('mdp');
            $checkPassword = $request->getPost('cmdp');
            $userName = $request->getPost('userName');
            $userData = array();
            $userData['mail'] = $mailUser;
            $userData['pass'] = $password;
            $userData['userName'] = $userName;
            $userData['role'] = null;
            $mailUsed = $user->checkMail($mailUser);
            $userNameUsed = $user->checkUserName($userName);

            //if true register user on session 
            if (!$mailUsed) {
                if (!$userNameUsed) {
                    if ($password == $checkPassword) {
                        if (filter_var($mailUser, FILTER_VALIDATE_EMAIL)) {
                            $errorMessage = 'Vous êtes connecté avec succés ';
                            $request->newSession("user", $userData);
                            $hashPassword = hash("sha256", $password);
                            $user->newUser($userName, $mailUser, $hashPassword);
                        } else {
                            $errorMessage = 'Le format de l\'email est incorrect ';
                        }
                    } else {
                        $errorMessage = 'Les mots de passe ne sont pas identique ';
                    }
                } else {
                    $errorMessage = 'Le nom d\'utilisateur entré n\'est pas disponible  ';
                }
            } else {
                $errorMessage = 'Cette email est déja utilisé';
            }
            return $this->render('user/register.html.twig', ['errorMessage' => $errorMessage]);
        }
        return $this->render('user/register.html.twig', ['errorMessage' => $errorMessage]);
    }
}
