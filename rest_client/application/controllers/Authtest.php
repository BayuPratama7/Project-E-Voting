<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authtest extends CI_Controller {

    public function index()
    {
        echo "<h1>Authtest Controller Working!</h1>";
        echo "<p>This means CodeIgniter can load controllers</p>";
    }

    public function login()
    {
        echo "<h1>Authtest Login Working!</h1>";
        echo "<p>This means routing to methods works</p>";
    }
}
