<?php 
namespace App;

use Blogue\Controller;

class BilletController extends Controller{

    public function __construct()
    {
        //call to get Route
    }

    public function getAllBillet(){
    }
    
    public function index(){
       $this->getAllBillet();
    }
  
 }