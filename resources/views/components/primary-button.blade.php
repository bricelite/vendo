<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-principale border border-transparent rounded-xl font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-principale focus:ring-offset-2 transition ease-in-out duration-150 hover:bg-principale/90']) }}>
    {{ $slot }}
</button>
