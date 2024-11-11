<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\NewContactNotification;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact.show');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);

        $contact = Contact::create($validated);

        // Yöneticiye bildirim gönder
        Mail::to(config('mail.admin_address'))->send(new NewContactNotification($contact));

        return redirect()->route('contact.show')
            ->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede size dönüş yapacağız.');
    }
}