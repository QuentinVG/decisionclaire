@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold leading-5 text-white shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-4 focus:ring-emerald-100'
            : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold leading-5 text-slate-600 transition duration-150 ease-in-out hover:bg-white hover:text-slate-950 focus:outline-none focus:ring-4 focus:ring-emerald-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
