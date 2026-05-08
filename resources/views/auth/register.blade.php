<x-guest-layout>
  {{-- Header Section --}}
  <div class="mb-8 text-center">
    <h2 class="text-xl font-bold">Daftar Akun Baru</h2>
    <p class="text-sm text-muted">Mulai kelola bengkel Anda secara profesional.</p>
  </div>

  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <!-- Name -->
    <div>
      <x-input-label for="name" :value="__('Nama Lengkap')" />
      <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus
        autocomplete="name" placeholder="Masukkan nama lengkap" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div>
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required
        autocomplete="username" placeholder="email@contoh.com" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div>
      <x-input-label for="password" :value="__('Password')" />
      <x-text-input id="password" class="block w-full" type="password" name="password" required
        autocomplete="new-password" placeholder="Min. 8 karakter" />
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div>
      <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
      <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation"
        required autocomplete="new-password" placeholder="Ulangi password" />
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="pt-2">
      <x-primary-button>
        <i class="fa-solid fa-user-plus text-sm"></i>
        {{ __('Daftar Sekarang') }}
      </x-primary-button>
    </div>

    <p class="text-center text-xs text-muted mt-6">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-brand-primary font-bold hover:underline">Login di sini</a>
    </p>
  </form>
</x-guest-layout>
