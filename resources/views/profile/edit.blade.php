@extends('layouts.app')

@section('title', 'Profil Saya — Agung Motor')

@section('content')
  <div class="py-24">
    <div class="max-w-7xl mx-auto px-6 space-y-8">

      {{-- Header --}}
      <div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Pengaturan Profil</h1>
        <p class="text-gray-500 mt-2">Kelola informasi akun dan keamanan Anda.</p>
      </div>

      <div class="grid lg:grid-cols-3 gap-8">

        {{-- Left Side: Info --}}
        <div class="space-y-6">
          <div class="glass-card p-8 text-center">
            <div
              class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-brand-primary to-red-800 flex items-center justify-center text-white font-bold text-3xl shadow-xl mb-4">
              {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <h3 class="text-xl font-bold text-white">{{ Auth::user()->name }}</h3>
            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
            <div class="mt-6 pt-6 border-t border-white/5">
              <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Terdaftar Sejak</p>
              <p class="text-sm text-gray-300 mt-1">{{ Auth::user()->created_at->format('d M Y') }}</p>
            </div>
          </div>

          <div class="glass-card p-6">
            <h4 class="font-bold text-sm text-white mb-4 uppercase tracking-wider">Navigasi Cepat</h4>
            <nav class="space-y-2">
              <a href="{{ route('admin.index') }}"
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 text-gray-500 hover:text-white transition-all text-sm">
                <i class="fa-solid fa-gauge w-5"></i> Dashboard Admin
              </a>
              <a href="{{ route('home') }}"
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 text-gray-500 hover:text-white transition-all text-sm">
                <i class="fa-solid fa-house w-5"></i> Kembali ke Home
              </a>
            </nav>
          </div>
        </div>

        {{-- Right Side: Forms --}}
        <div class="lg:col-span-2 space-y-8">

          <div class="glass-card p-8">
            <div class="max-w-xl">
              @include('profile.partials.update-profile-information-form')
            </div>
          </div>

          <div class="glass-card p-8">
            <div class="max-w-xl">
              @include('profile.partials.update-password-form')
            </div>
          </div>

          <div class="glass-card p-8 border-brand-primary/20">
            <div class="max-w-xl">
              @include('profile.partials.delete-user-form')
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Styling overrides for default Breeze forms inside our glass cards */
    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
      background-color: rgba(255, 255, 255, 0.05) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: white !important;
      border-radius: 0.75rem !important;
    }

    input:focus {
      border-color: #B33232 !important;
      ring-color: #B33232 !important;
    }

    label {
      color: #9CA3AF !important;
      font-size: 0.875rem !important;
      font-weight: 500 !important;
    }

    .text-gray-600 {
      color: #6B7280 !important;
    }

    .text-gray-800 {
      color: #F3F4F6 !important;
    }

    .bg-white {
      background-color: transparent !important;
    }

    button.inline-flex {
      background-color: #B33232 !important;
      border-radius: 0.75rem !important;
      padding: 0.75rem 1.5rem !important;
      font-weight: 600 !important;
      transition: all 0.2s !important;
    }

    button.inline-flex:hover {
      background-color: #8E2828 !important;
      transform: translateY(-1px) !important;
    }
  </style>
@endpush
