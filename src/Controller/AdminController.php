<?php

namespace App\Controller;

use Exception;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;

class AdminController extends Controller
{

    private $request, $postManager;
    public function __construct()
    {
        $this->request = new Request;
        $this->postManager = new PostManager;
    }

    public function index()
    {
        $posts = $this->postManager->getPosts();
        $this->render('admin/admin.html.twig', ['posts' => $posts]);
    }

    public function addPost()
    {

        $methode = $this->request->getMethode();
        $message = '';
        if ($methode == 'POST') {
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');

            $this->postManager->addPost($title, $content);
            return $this->render('admin/addPost.html.twig', ["message" => $message]);
        }



        return  $this->render('admin/addPost.html.twig', ["message" => $message]);
    }

    public function update()
    {
        $path = $this->request->getRequest();
        $id = array();
        preg_match('#[1-9]{1,}#', $path, $id);

        if (count($id) <= 1) {
            $post = $this->postManager->getPost($id[0]);
            if ($this->request->getMethode() == 'POST') {
                $title = $this->request->getPost('title');
                $content = $this->request->getPost('content');

                $this->postManager->updatePost($title, $content, $id[0]);
            }
            return $this->render('admin/billet.html.twig', ['post' => $post]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }

    public function delete()
    {
        $path = $this->request->getRequest();
        $id = array();
        preg_match('#[1-9]{1,}#', $path, $id);

        if (count($id) <= 1) {
            $this->postManager->deletePost($id[0]);
            $posts = $this->postManager->getPosts();
            return $this->render('admin/admin.html.twig', ['posts' => $posts]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }
}
