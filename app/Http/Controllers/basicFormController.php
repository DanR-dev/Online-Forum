<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class basicFormController extends Controller
{
    public function createForm(Request $request) {
        return view('components/basicInputForm');
    }
    
    public function processCreateForm(Request $request) {

        // Form validation
        $this->validate($request, [
            'title' => 'required',
         ]);
        //  Store data in database
        Role::create($request->all());

        // 
        return back()->with('success', 'We have received your message and would like to thank you for writing to us.');
    }

    
    public function editForm(Request $request) {
        return view('components/basicEditForm');
    }
    
    public function processEditForm(Request $request) {

        if($request->mode == "Edit")
        {

            // Form validation
            $this->validate($request, [
                'newTitle' => 'required',
                'title' => 'exists:roles',
             ]);
            //  Update data in database
            $role = Role::where('title', $request->title)->first();
            $role->title = $request->newTitle;
            $role->save();
            return back()->with('success');
        }else{

            // Form validation
            $this->validate($request, [
                'title' => 'exists:roles',
             ]);
            //  Update data in database
            $role = Role::where('title', $request->title)->first();
            $role->delete();
            return back()->with('success');
        }

    }
}
