<?php 
namespace App\Controller;

use Blogue\Controller;

class HomeController extends Controller{

    public function contact(){
        $this->render('contact/index.html.twig');
    }

    public function index(){
        $this->render('base.html.twig');
    }
  
 }
