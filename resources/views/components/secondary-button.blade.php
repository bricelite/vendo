<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-3 border border-principale rounded-xl font-semibold text-sm text-principale focus:outline-none focus:ring-2 focus:ring-principale focus:ring-offset-2 transition ease-in-out duration-150 hover:bg-principale/5']) }}>
    {{ $slot }}
</button>
