<?php
namespace App\Controller;

use App\model\CommentsManager;
use Exception;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;

class CommentsController extends Controller{

    private $request, $commentsManager, $postManager;
    public function __construct()
    {
        $this->request = new Request;
        $this->commentsManager = new CommentsManager;
        $this->postManager = new PostManager;


    }
    
    public function postComment(){
        $path = $this->request->getRequest();
        $id = array();
        preg_match('#[1-9]{1,}#', $path, $id);
        $post = $this->postManager->getPost($id[0]);

        if (count($id) <= 1) {
            if ($this->request->getMethode() == 'POST') {
                $userSession = $this->request->getSession('user');

                $content = $this->request->getPost('content');
                $id_post = $id[0];
                $user_Name = $userSession['userName'];
                $this->commentsManager->addComment($id_post, $user_Name, $content);
            }
            return $this->render('billet.html.twig', ['post' => $post, 'log'=> true]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }
}