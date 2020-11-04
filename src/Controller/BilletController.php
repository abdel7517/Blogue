<?php 
namespace App;

use Blogue\Controller;
use Blogue\Request;

class BilletController extends Controller{
    private $request;
    public function __construct()
    {
        $this->request = New Request;
    }

    public function getAllBillet(){
        $this->render('test.html.twig');
    }
    
    public function index(){
       $this->getAllBillet();
    }
  
 }