@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'bg-brand-surface border border-brand-primary/10 light:border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none transition-all placeholder:text-muted/50',
]) !!}>
