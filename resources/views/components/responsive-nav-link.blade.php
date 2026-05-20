@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-start text-base font-semibold text-emerald-900 transition duration-150 ease-in-out focus:outline-none focus:ring-4 focus:ring-emerald-100'
            : 'block w-full rounded-md border border-transparent px-4 py-3 text-start text-base font-semibold text-slate-700 transition duration-150 ease-in-out hover:border-slate-200 hover:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
