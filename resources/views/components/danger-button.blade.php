<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-3 bg-alerte border border-transparent rounded-xl font-semibold text-sm text-white focus:outline-none focus:ring-2 focus:ring-alerte focus:ring-offset-2 transition ease-in-out duration-150 hover:bg-alerte/90']) }}>
    {{ $slot }}
</button>
