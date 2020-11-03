<?php 
namespace App;

use Blogue\Controller;

class HomeController extends Controller{

    public function getAllBillet(){
    }
    public function index(){
        $this->render('base.html.twig', ['test'=> 'carré les test ']);
    }
  
 }
