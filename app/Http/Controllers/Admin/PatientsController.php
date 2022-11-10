<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientsController extends Controller
{ 
    function index(){
        $title    = 'patients list';
        $doctors  = User::where('role',2)->get();
        $data     = compact('title','doctors');
        return view('admin.patients.index',$data);
    }
}
