<nav class="text-gray-500 mb-8" aria-label="Breadcrumb">
    <ol class="list-none p-0 inline-flex flex-wrap items-center">
        <li class="flex items-center">
            <a href="{{ route('home') }}" class="hover:text-blue-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </a>
        </li>

        @foreach($items as $item)
            <li class="flex items-center">
                <svg class="w-5 h-5 mx-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-blue-600">{{ $item['title'] }}</a>
                @else
                    <span class="text-gray-700 font-medium">{{ $item['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>