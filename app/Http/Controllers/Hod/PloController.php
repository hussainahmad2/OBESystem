<?php
namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Faculty;
use App\Models\Role;

class PloController extends Controller
{
    public function edit($number)
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = \App\Models\Role::whereIn('id', json_decode($faculty->duties))->get();

        return view('lecturar.hod.edit_plo', compact('number', 'designation', 'duties'));
    }

    public function list()
    {
        $user = Auth::user();
        $faculty = Faculty::where('user_id', $user->id)->first();
        $designation = $faculty->designation;
        $duties = Role::whereIn('id', json_decode($faculty->duties))->get();
        
        return view('lecturar.hod.plo_list', compact('designation', 'duties'));
    }
} 