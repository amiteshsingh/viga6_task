<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        echo "adlkfjadslf";
        return view('welcome_message');
    }
}
