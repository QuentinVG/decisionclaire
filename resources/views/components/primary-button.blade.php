<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-md border border-transparent bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition ease-in-out duration-150 hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-100']) }}>
    {{ $slot }}
</button>
