@component('mail::message')
# Mesajınıza Yanıt

Sayın {{ $contact->name }},

İletişim formu üzerinden göndermiş olduğunuz mesaja yanıtımız aşağıdadır:

{{ $contact->reply }}

Teşekkür ederiz.

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent