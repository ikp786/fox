<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    function index(){
        $title    = 'doctors list';
        $doctors  = User::where('role',3)->get();
        $data     = compact('title','doctors');
        return view('admin.doctors.index',$data);
    }

    function mentorRequest(){
        $title    = 'Mentor Request';
        $doctors  = User::where('role',3)->get();
        $data     = compact('title','doctors');
        return view('admin.doctors.mentor_request',$data);
    }
}
