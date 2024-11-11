@component('mail::message')
# Yeni İletişim Formu Mesajı

**Gönderen:** {{ $contact->name }}
**E-posta:** {{ $contact->email }}
**Telefon:** {{ $contact->phone ?? '-' }}
**Konu:** {{ $contact->subject }}

**Mesaj:**
{{ $contact->message }}

@component('mail::button', ['url' => route('admin.contacts.show', $contact)])
Mesajı Görüntüle
@endcomponent

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent