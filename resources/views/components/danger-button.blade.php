<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-semibold text-white transition ease-in-out duration-150 hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-100']) }}>
    {{ $slot }}
</button>
