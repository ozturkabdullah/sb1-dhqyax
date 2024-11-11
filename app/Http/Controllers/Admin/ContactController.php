<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\ContactReply;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }
        return view('admin.contacts.show', compact('contact'));
    }

    public function reply(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'reply' => 'required'
        ]);

        $contact->update([
            'reply' => $validated['reply'],
            'replied_at' => now()
        ]);

        // E-posta gönderimi
        Mail::to($contact->email)->send(new ContactReply($contact));

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Yanıt başarıyla gönderildi.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Mesaj başarıyla silindi.');
    }
}