@component('mail::message')
# {{ $status === 'success' ? 'Yeni Başarılı Ödeme' : 'Başarısız Ödeme Bildirimi' }}

**Kiralama Bilgileri:**
- Kullanıcı: {{ $rental->user->name }} ({{ $rental->user->email }})
- Kategori: {{ $rental->category->name }}
- Başlangıç: {{ $rental->start_date->format('d.m.Y') }}
- Bitiş: {{ $rental->end_date->format('d.m.Y') }}
- Toplam Tutar: {{ number_format($rental->total_amount, 2) }} ₺
- Ödeme Yöntemi: {{ $rental->payment->payment_method === 'credit_card' ? 'Kredi Kartı' : 'Havale/EFT' }}

@component('mail::button', ['url' => route('admin.rentals.show', $rental)])
Kiralama Detaylarını Görüntüle
@endcomponent

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent