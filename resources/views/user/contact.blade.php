@extends('layouts.app')

@section('title', 'Hubungi Kami — Agung Motor')

@section('content')

  <!-- ===== PAGE HERO ===== -->
  <section class="relative pt-40 pb-20 bg-brand-dark overflow-hidden">
    <div class="absolute inset-0 radial-glow pointer-events-none opacity-60"></div>
    <div class="max-w-7xl mx-auto px-6 text-center space-y-4 relative z-10">
      <span
        class="inline-block px-4 py-1.5 rounded-full glass text-brand-primary text-xs font-bold uppercase tracking-widest">Kontak</span>
      <h1 class="text-5xl md:text-7xl font-extrabold">Hubungi <span class="text-brand-primary">Kami</span></h1>
      <p class="text-gray-500 max-w-xl mx-auto text-lg">Siap membantu Anda setiap hari. Booking servis atau tanya-tanya via
        WhatsApp.</p>
    </div>
  </section>

  <!-- ===== CONTACT SECTION ===== -->
  <section class="py-24 bg-brand-dark">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16">

      <!-- Info -->
      <div class="space-y-8">
        <h2 class="text-3xl font-bold">Informasi <span class="text-brand-primary">Bengkel</span></h2>

        <div class="space-y-5">
          <div class="flex items-start gap-5 glass-card p-5">
            <div
              class="w-12 h-12 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary flex-shrink-0">
              <i class="fa-solid fa-location-dot text-xl"></i>
            </div>
            <div>
              <h4 class="font-bold mb-1">Alamat Bengkel</h4>
              <p class="text-sm text-gray-500">Jl. Karangmulya II No.74, Drajat, Kec. Kesambi, Kota Cirebon, Jawa Barat
                45133</p>
            </div>
          </div>

          <div class="flex items-start gap-5 glass-card p-5">
            <div
              class="w-12 h-12 rounded-xl bg-brand-whatsapp/10 flex items-center justify-center text-brand-whatsapp flex-shrink-0">
              <i class="fa-brands fa-whatsapp text-xl"></i>
            </div>
            <div>
              <h4 class="font-bold mb-1">WhatsApp</h4>
              <p class="text-sm text-gray-500">+62 812 3456 7890</p>
              <a href="https://wa.me/6281234567890"
                class="text-brand-whatsapp text-xs font-bold mt-2 inline-block hover:underline">
                Chat Sekarang →
              </a>
            </div>
          </div>

          <div class="flex items-start gap-5 glass-card p-5">
            <div
              class="w-12 h-12 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary flex-shrink-0">
              <i class="fa-solid fa-clock text-xl"></i>
            </div>
            <div>
              <h4 class="font-bold mb-1">Jam Operasional</h4>
              <p class="text-sm text-gray-500">Senin – Sabtu: 08.00 – 17.00 WIB</p>
            </div>
          </div>
        </div>

        <a href="https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+servis+motor"
          class="btn-whatsapp inline-flex">
          <i class="fa-brands fa-whatsapp text-xl"></i>
          Konsultasi via WhatsApp
        </a>
      </div>

      <!-- Map Placeholder -->
      <div class="glass-card overflow-hidden min-h-[480px] flex flex-col">
        <div class="flex-1 bg-gray-800/50 flex items-center justify-center text-gray-600 text-sm italic">
          <div class="text-center space-y-3">
            <i class="fa-solid fa-map-location-dot text-5xl text-gray-700"></i>
            <p>[ Embed Google Maps di sini ]</p>
            <code class="text-xs text-gray-600">&lt;iframe src="https://maps.google.com/..."&gt;</code>
          </div>
        </div>
      </div>

    </div>
  </section>

@endsection
