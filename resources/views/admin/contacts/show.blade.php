@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">İletişim Mesajı Detayı</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mesaj Bilgileri</h3>
            
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Ad Soyad</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">E-posta</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->email }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Telefon</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->phone ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Konu</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->subject }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Mesaj</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $contact->message }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tarih</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contact->created_at->format('d.m.Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Yanıt</h3>

            @if($contact->reply)
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-sm text-gray-600 mb-2">Yanıtlandı: {{ $contact->replied_at->format('d.m.Y H:i') }}</p>
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $contact->reply }}</p>
                </div>
            @endif

            <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label for="reply" class="block text-sm font-medium text-gray-700">Yanıtınız</label>
                        <textarea name="reply" id="reply" rows="6" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('reply') }}</textarea>
                        @error('reply')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Yanıtı Gönder
                        </button>

                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                    onclick="return confirm('Bu mesajı silmek istediğinize emin misiniz?')">
                                Mesajı Sil
                            </button>
                        </form>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection