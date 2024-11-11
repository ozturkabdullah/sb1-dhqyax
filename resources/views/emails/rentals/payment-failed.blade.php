@component('mail::message')
# Ödeme İşlemi Başarısız

Sayın {{ $rental->user->name }},

{{ $rental->category->name }} kategorisi için yapmış olduğunuz kiralama ödemesi maalesef başarısız olmuştur.

**Kiralama Detayları:**
- Kategori: {{ $rental->category->name }}
- Başlangıç: {{ $rental->start_date->format('d.m.Y') }}
- Bitiş: {{ $rental->end_date->format('d.m.Y') }}
- Toplam Tutar: {{ number_format($rental->total_amount, 2) }} ₺

Ödeme işlemini tekrar denemek için aşağıdaki butonu kullanabilirsiniz.

@component('mail::button', ['url' => route('rentals.payment', $rental)])
Ödemeyi Tekrar Dene
@endcomponent

Sorun yaşamaya devam ederseniz, lütfen bizimle iletişime geçin.

Saygılarımızla,<br>
{{ config('app.name') }}
@endcomponent