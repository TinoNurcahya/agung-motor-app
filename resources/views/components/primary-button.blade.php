<button
  {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full bg-brand-primary hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all active:scale-95 shadow-lg shadow-brand-primary/20 flex items-center justify-center gap-2']) }}>
  {{ $slot }}
</button>
