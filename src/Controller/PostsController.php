<?php

namespace App\Controller;

use App\model\CommentsManager;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;
use Exception;

class PostsController extends Controller
{
    private $request, $postManager, $commentsManager;
    public function __construct()
    {
        $this->request = new Request;
        $this->postManager = new PostManager;
        $this->commentsManager = new CommentsManager;
    }
    public function getPost()
    {
        $path = $this->request->getRequest();
        $id = array();
        $userSession = $this->request->getSession('user');
        // get the id from url 
        preg_match('#[1-9]{1,}#', $path, $id);
        if(count($id) <= 1 )
        {
            $post = $this->postManager->getPost($id[0]);
            $comments = $this->commentsManager->getComments();
            if($userSession !== ""){
                return $this->render('billet.html.twig', ['post'=> $post, 'log'=> true, 'comments'=>$comments]);
            }
            return $this->render('billet.html.twig', ['post'=> $post, 'comments'=>$comments]);

        }else
        {
            new Exception('L\'url doit contenire un seul id '. __FILE__ . 'ligne : '. __LINE__);
        }
    }

    public function getAllPosts()
    {
        $arrayPosts = $this->postManager->getPosts();
        return $this->render('billets.html.twig', ['posts' => $arrayPosts]);
    }
}
