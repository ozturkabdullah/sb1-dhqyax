@component('mail::message')
# Ödemeniz Başarıyla Alındı

Sayın {{ $rental->user->name }},

{{ $rental->category->name }} kategorisi için yapmış olduğunuz kiralama ödemesi başarıyla alınmıştır.

**Kiralama Detayları:**
- Kategori: {{ $rental->category->name }}
- Başlangıç: {{ $rental->start_date->format('d.m.Y') }}
- Bitiş: {{ $rental->end_date->format('d.m.Y') }}
- Toplam Tutar: {{ number_format($rental->total_amount, 2) }} ₺

Kiralama sürecinizi "Kiralamalarım" sayfasından takip edebilirsiniz.

@component('mail::button', ['url' => route('rentals.my-rentals')])
Kiralamalarımı Görüntüle
@endcomponent

Teşekkür ederiz.

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent