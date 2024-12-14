<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\User;
use Illuminate\Support\Facades\Hash;

class InboxContactController extends Controller
{
    public function contactMessage()
    {
    	$messages = Contact::latest()->paginate(15);

    	return view('inbox.index', compact('messages'));
    }

    public function readMessage(Contact $contact)
    {
    	if ($contact->status == 0) {
    		Contact::where('id', $contact->id)->update(['status' => 1]);
    	}

    	return view('inbox.view', compact('contact'));
    }

    public function destroy($id)
    {
    	$message = Contact::find($id);
    	$done = $message->delete();
    	
        if ($done) {
            $notify = deleteNotify('Contact message');
        }else{
            $notify = errorNotify('Contact message');
        }

    	return redirect()->back()->with($notify);
    }
}
