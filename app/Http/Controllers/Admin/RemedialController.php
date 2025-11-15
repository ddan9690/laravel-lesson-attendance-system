<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RemedialController 
{
    public function index()
    {
        return view('admin.remedial.index');
    }
}
