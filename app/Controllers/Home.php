<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Aplikasi Kasir'
        ];
        return view('home', $data);
    }
}
