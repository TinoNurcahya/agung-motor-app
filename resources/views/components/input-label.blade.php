@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold uppercase tracking-widest text-muted mb-2']) }}>
  {{ $value ?? $slot }}
</label>
