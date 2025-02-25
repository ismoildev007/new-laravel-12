<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }
    public function update(Request $request, Contact $contact)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string|max:25',
            'phone2' => 'nullable|string|max:25',
            'address' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'telegram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'tik_tok' => 'nullable|url|max:255',
        ]);

        $contact->update($validatedData);

        return redirect()->back()->with('success', 'Contact successfully updated!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->back()->with('success', 'Contact successfully deleted!');
    }
}
