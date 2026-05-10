<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-surface border border-brand-primary/20 rounded-xl font-semibold text-xs text-main uppercase tracking-widest shadow-sm hover:bg-brand-primary/10 focus:outline-none focus:ring-2 focus:ring-brand-primary transition-all duration-150']) }}>
    {{ $slot }}
</button>
