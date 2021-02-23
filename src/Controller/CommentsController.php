<?php

namespace App\Controller;

use App\model\CommentsManager;
use Exception;
use Blogue\Request;
use Blogue\Controller;
use App\model\PostManager;

class CommentsController extends Controller
{

    private  $commentsManager, $postManager, $userSession, $aleradyPosted = false;
    public  $request;
    public function __construct()
    {
        $this->request = new Request;
        $this->commentsManager = new CommentsManager;
        $this->postManager = new PostManager;
        $this->userSession = $this->request->getSession('user');
    }

    public function reportComment()
    {
        $userSession = $this->userSession;
        //get the id of comment and post 
        $id = $this->getParametersUrl();
        $id_post = $id[0];
        $id_comment = $id[1];
        //get reports of this comment on db 
        $reportSerialised = $this->commentsManager->getReport($id_comment, $id_post);
        $UsersNameReport = unserialize($reportSerialised);
        $post = $this->postManager->getPost($id[0]);
        $comments = $this->commentsManager->getCommentsOfPost($id_post);
        $message = "";
        if( $UsersNameReport !== false){
            foreach ($UsersNameReport as $UserNameReport) {
                if ($UserNameReport == $userSession['userName']) {
                    //already signal 
                    $message =  'Vous avez déja signalez ce commentaire';
                    $this->aleradyPosted = true;
                    return $this->render('billet.html.twig', [
                        'post'=> $post, 'log'=> true, 'comments'=> $comments, 
                        'id' => $id[0],'user_name' => $userSession['userName'],
                        'message'=> $message
                        ]);
                } 
            }
        }
        if($this->aleradyPosted == false){
            $UsersNameReport[] = $userSession['userName'];
            $UsersNameReportSerialized = serialize($UsersNameReport);
            $this->commentsManager->addReport($UsersNameReportSerialized,$id_comment, $id_post);
            $message = 'Vous avez bien signalez ce commentaire';
            return $this->render('billet.html.twig', [
                'post'=> $post, 'log'=> true, 'comments'=> $comments, 
                'id' => $id[0],'user_name' => $userSession['userName'],
                'message'=> $message
                ]);
        }
    }

    public function deleteComment()
    {
        $id = $this->getMultipleId();
        $id_comment = $id[0][0];
        $id_post = $id[0][1];
        $this->commentsManager->deleteComment($id_comment);

        $post = $this->postManager->getPost($id_post);
        $comments = $this->commentsManager->getCommentsOfPost($id_post);
        $user_Name = $this->userSession['userName'];

        return $this->render('billet.html.twig', [
            'post' => $post, 'log' => true, 'comments' => $comments, 'user_name' => $user_Name
        ]);
    }

    public function postComment()
    {
        $id_post = $this->getId();
        $post = $this->postManager->getPost($id_post[0]);
        $content = $this->request->getPost('content');
        $user_Name = $this->userSession['userName'];
        if (count($id_post) <= 1) {
            if ($this->request->getMethode() == 'POST') {
                $this->commentsManager->addComment($id_post[0], $user_Name, $content);
            }
            $comments = $this->commentsManager->getCommentsOfPost($id_post[0]);

            return $this->render('billet.html.twig', [
                'post' => $post, 'log' => true, 'comments' => $comments, 'user_name' => $user_Name
            ]);
        } else {
            new Exception('L\'url doit contenir un seul id ' . __FILE__ . 'ligne : ' . __LINE__);
        }
    }



   
}
