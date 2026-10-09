@props([
    'user' => null,
    'size' => 36,
    'class' => '',
    'alt' => null,
])

@php
    $user = $user ?? auth()->user();
    $name = $user?->name ?? 'User';
    $alt = $alt ?? $name;
    $photoUrl = $user?->photo_url;
    $initial = strtoupper(substr($name, 0, 1));
    $dimension = is_numeric($size) ? "{$size}px" : $size;
    $fontSize = is_numeric($size) ? max(10, round($size * 0.42)) . 'px' : '0.85rem';
@endphp

<div class="user-avatar-comp rounded-circle overflow-hidden d-inline-flex align-items-center justify-content-center shadow-sm flex-shrink-0 {{ $class }}"
     style="width: {{ $dimension }}; height: {{ $dimension }}; min-width: {{ $dimension }}; min-height: {{ $dimension }}; background: linear-gradient(135deg, #071b35 0%, #1769d5 100%); font-size: {{ $fontSize }}; font-weight: 700; color: #ffffff; line-height: 1;"
     title="{{ $name }}">
    @if ($photoUrl)
        <img src="{{ $photoUrl }}"
             alt="{{ $alt }}"
             class="w-100 h-100 object-fit-cover rounded-circle"
             onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
        <span class="d-none w-100 h-100 align-items-center justify-content-center">{{ $initial }}</span>
    @else
        <span>{{ $initial }}</span>
    @endif
</div>
