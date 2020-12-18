<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class basicFormController extends Controller
{
    public function createForm(Request $request) {
        return view('components/basicInputForm');
    }
    
    public function process(Request $request) {

        // Form validation
        $this->validate($request, [
            'title' => 'required',
         ]);
        //  Store data in database
        Role::create($request->all());

        // 
        return back()->with('success', 'We have received your message and would like to thank you for writing to us.');
    }
}
