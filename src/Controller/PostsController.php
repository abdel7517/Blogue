<?php

namespace App\Controller;

use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;
use Exception;

class PostsController extends Controller
{
    private $request, $postManager;
    public function __construct()
    {
        $this->request = new Request;
        $this->postManager = new PostManager;
    }
    public function getPost()
    {
        $path = $this->request->getRequest();
        $id = array();
        preg_match('#[1-9]{1,}#', $path, $id);
        if(count($id) <= 1 )
        {
            $post = $this->postManager->getPost($id[0]);
            $this->render('billet.html.twig', ['post'=> $post]);

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
