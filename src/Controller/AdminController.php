<?php

namespace App\Controller;

use App\model\CommentsManager;
use Exception;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;

class AdminController extends Controller
{

    private  $postManager, $commentManager;
    public $request;
    public function __construct()
    {
        $this->request = new Request;
        $this->postManager = new PostManager;
        $this->commentManager = new CommentsManager;
    }

    public function index()
    {
        $posts = $this->postManager->getPosts();
        $commentsReported = $this->getCommentsReported();
        $this->render(
            'admin/admin.html.twig',
            ['posts' => $posts, 'comments' => $commentsReported]
        );
    }

    public function addPost()
    {

        $methode = $this->request->getMethode();
        $message = 'Votre introduction doit contenir moins de 220 caractères';
        if ($methode == 'POST') {
            // Constantes
            define('TARGET', '/files/');    // Repertoire cible
            define('MAX_SIZE', 100000);    // Taille max en octets du img
            define('WIDTH_MAX', 800);    // Largeur max de l'image en pixels
            define('HEIGHT_MAX', 800);    // Hauteur max de l'image en pixels

            // Tableaux de donnees
            $tabExt = array('jpg', 'gif', 'png', 'jpeg');    // Extensions autorisees
            $infosImg = array();

            // Variables
            $extension = '';
            $message = '';
            $nomImage = '';
            $rep = $this->request->getPath() . DIRECTORY_SEPARATOR . $this->rootFolder(). DIRECTORY_SEPARATOR . 'public/asset/images/';

            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            $intro = $this->request->getPost('intro');
            $reads = $this->request->getPost('reads');
            $img = $this->request->getPost('img');
            if (strlen($intro) < 220) {
                if ($reads > 0) {
                    // On verifie si le champ est rempli
                    if (!empty($_FILES['img']['name'])) {
                        // Recuperation de l'extension du img
                        $extension  = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

                        // On verifie l'extension du img
                        if (in_array(strtolower($extension), $tabExt)) {
                            // On recupere les dimensions du img
                            $infosImg = getimagesize($_FILES['img']['tmp_name']);

                            // On verifie le type de l'image
                            if ($infosImg[2] >= 1 && $infosImg[2] <= 14) {

                                // Parcours du tableau d'erreurs
                                if (
                                    isset($_FILES['img']['error'])
                                    && UPLOAD_ERR_OK === $_FILES['img']['error']
                                ) {
                                    // On renomme le img
                                    $nomImage = md5(uniqid()) . '.' . $extension;
                                    $pathImage = $rep . $nomImage;
                                    // Si c'est OK, on teste l'upload
                                    if (move_uploaded_file($_FILES['img']['tmp_name'], $pathImage)) {
                                        $message = 'Votre annonce est en ligne !';
                                        $this->postManager->addPost($title, $content, $intro, $reads, $nomImage);
                                        return  $this->render('admin/addPost.html.twig', ["message" => $message]);
                                        exit();
                                    } else {
                                        // Sinon on affiche une erreur systeme
                                        $message = 'Problème lors de l\'upload !';
                                    }
                                } else {
                                    $message = 'Une erreur interne a empêché l\'upload de l\'image';
                                }
                            } else {
                                // Sinon erreur sur le type de l'image
                                $message = 'Le img à uploader n\'est pas une image !';
                            }
                        } else {
                            // Sinon on affiche une erreur pour l'extension
                            $message = 'L\'extension du img est incorrecte !';
                        }
                    } 
                } else {
                    $message = "Le temps de lecture ne peut être négatif";
                }
            }

            return $this->render('admin/addPost.html.twig', ["message" => $message]);
        }
        return  $this->render('admin/addPost.html.twig', ["message" => $message]);
    }

    public function update()
    {
        $path = $this->request->getRequest();
        $id = $this->getId();
        $comments = $this->commentManager->getComments($id[0]);


        if (count($id) <= 1) {
            $post = $this->postManager->getPost($id[0]);
            if ($this->request->getMethode() == 'POST') {
                $title = $this->request->getPost('title');
                $content = $this->request->getPost('content');

                $this->postManager->updatePost($title, $content, $id[0]);
            }
            return $this->render('admin/billet.html.twig', ['post' => $post, 'comments' => $comments]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }

    public function delete()
    {
        $id = $this->getId();
        $posts = $this->postManager->getPosts();
        if (count($id) <= 1) {
            $this->postManager->deletePost($id[0]);
            return $this->render('admin/admin.html.twig', ['posts' => $posts]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }

    public function getCommentsReported()
    {
        $comments = $this->commentManager->getComments();
        $commentsReported = array();
        foreach ($comments as $comment) {
            if ($comment['reports'] !== null) {
                $UsersNameReport = unserialize($comment['reports']);
                $numberOfReport = count($UsersNameReport);
                $comment['nbOfReport'] = $numberOfReport;
                $commentsReported[] = $comment;
            }
        }
        $columns = array_column($commentsReported, 'nbOfReport');
        array_multisort($columns, SORT_DESC, $commentsReported);
        // krsort($commentsReported);
        return $commentsReported;
    }
}
