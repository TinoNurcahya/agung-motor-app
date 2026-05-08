<x-guest-layout>
  {{-- Header Section --}}
  <div class="mb-8 text-center">
    <h2 class="text-xl font-bold">Selamat Datang</h2>
    <p class="text-sm text-muted">Masuk ke akun Anda untuk mengelola bengkel.</p>
  </div>

  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email Address -->
    <div>
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus
        autocomplete="username" placeholder="Masukkan email Anda" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div>
      <div class="flex items-center justify-between mb-2">
        <x-input-label for="password" :value="__('Password')" class="mb-0" />
        @if (Route::has('password.request'))
          <a class="text-[10px] uppercase font-bold text-brand-primary hover:underline"
            href="{{ route('password.request') }}">
            {{ __('Lupa Password?') }}
          </a>
        @endif
      </div>

      <x-text-input id="password" class="block w-full" type="password" name="password" required
        autocomplete="current-password" placeholder="••••••••" />

      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
      <label for="remember_me" class="inline-flex items-center cursor-pointer group">
        <input id="remember_me" type="checkbox"
          class="rounded border-brand-primary/20 bg-brand-surface text-brand-primary shadow-sm focus:ring-brand-primary"
          name="remember">
        <span
          class="ms-2 text-xs font-semibold text-muted group-hover:text-brand-primary transition-colors">{{ __('Ingat saya') }}</span>
      </label>
    </div>

    <div class="pt-2">
      <x-primary-button>
        <i class="fa-solid fa-sign-in text-sm"></i>
        {{ __('Masuk Sekarang') }}
      </x-primary-button>
    </div>

    @if (Route::has('register'))
      <p class="text-center text-xs text-muted mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-primary font-bold hover:underline">Daftar Gratis</a>
      </p>
    @endif
  </form>
</x-guest-layout>
