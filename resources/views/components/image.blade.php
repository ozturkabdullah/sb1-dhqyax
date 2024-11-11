@props([
    'src',
    'alt',
    'size' => 'medium',
    'class' => '',
    'loading' => 'lazy',
    'width' => null,
    'height' => null,
    'placeholder' => true
])

@php
    $path = $src;
    if ($src) {
        $directory = dirname($path);
        $filename = basename($path);
        $path = $directory . '/' . $size . '/' . $filename;
        
        // Thumb versiyonunu placeholder olarak kullan
        $thumbPath = $directory . '/thumb/' . $filename;
    }
@endphp

<div class="relative overflow-hidden {{ $class }}" 
     style="{{ $width ? 'width: '.$width.'px;' : '' }} {{ $height ? 'height: '.$height.'px;' : '' }}">
    @if($placeholder && $src)
        <img src="{{ asset('storage/' . $thumbPath) }}"
             alt="{{ $alt }}"
             class="absolute inset-0 w-full h-full object-cover blur-up-fade"
             aria-hidden="true"
             loading="eager">
    @endif
    
    <img src="{{ $src ? asset('storage/' . $path) : '' }}"
         alt="{{ $alt }}"
         {{ $attributes->merge([
             'class' => 'w-full h-full object-cover transition-opacity duration-300' . 
                       ($placeholder ? ' opacity-0 loaded:opacity-100' : '')
         ]) }}
         loading="{{ $loading }}"
         @if($width) width="{{ $width }}" @endif
         @if($height) height="{{ $height }}" @endif
         onload="this.classList.add('loaded')">
</div>