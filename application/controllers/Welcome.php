<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function index()
    {
        // $this->template->load('Default', 'pages/Home', $this->data);
        $this->load->view('welcome_message');
    }
}
