<?php

namespace App\Controller;

use App\model\CommentsManager;
use Exception;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;

class AdminController extends Controller
{

    private $request, $postManager, $commentManager;
    public function __construct()
    {
        $this->request = new Request;
        $this->postManager = new PostManager;
        $this->commentManager = new CommentsManager;
    }

    public function index()
    {
        $posts = $this->postManager->getPosts();
        $this->getCommentsReported();
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
        $posts = $this->postManager->getPosts();
        if (count($id) <= 1) {
            $this->postManager->deletePost($id[0]);
            return $this->render('admin/admin.html.twig', ['posts' => $posts]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }

    public function getCommentsReported(){
        $comments = $this->commentManager->getComments();
        $commentsReported = array();
        foreach($comments as $comment){
            if($comment['reports'] !== null ){
                // $dataAboutReport = array();
                $UsersNameReport = unserialize($comment['reports']);
                $numberOfReport = count($UsersNameReport);
                $comment['nbOfReport'] =$numberOfReport;
                $commentsReported[$numberOfReport] = $comment;
                // $commentsReported[] = $dataAboutReport;
            }
        }
        krsort($commentsReported);
        $posts = $this->postManager->getPosts();
        return $this->render('admin/admin.html.twig', [
            'posts' => $posts, 'comments'=> $commentsReported
            ]);

    }
}
