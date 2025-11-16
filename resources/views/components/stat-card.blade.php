@props(['value', 'label', 'icon', 'color' => 'indigo'])

@php
    $colors = [
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'green'  => 'bg-green-100 text-green-600',
        'rose'   => 'bg-rose-100 text-rose-600',
        'purple' => 'bg-purple-100 text-purple-600',
    ];

    $c = $colors[$color] ?? $colors['indigo'];
@endphp

<div class="bg-white rounded-2xl shadow border border-slate-100 p-5">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-slate-500 text-sm">{{ $label }}</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $value }}</p>
        </div>

        <div class="w-10 h-10 grid place-items-center rounded-lg {{ $c }}">
            <i class="fas {{ $icon }}"></i>
        </div>
    </div>
</div>
