@extends('layouts.app')

@section('title', 'Hubungi Kami — Agung Motor Premium')

@section('content')

  <!-- ===== CINEMATIC HERO ===== -->
  <section class="relative min-h-[60vh] flex items-center justify-center bg-main overflow-hidden">
    <!-- Subtle Contact Glow -->
    <div class="absolute inset-0 contact-hero-bg pointer-events-none z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-brand-primary/10 rounded-full blur-[150px] pointer-events-none z-0"></div>
    
    <div class="max-w-7xl mx-auto px-6 text-center space-y-6 relative z-10 w-full flex flex-col items-center mt-20">
      <span class="inline-block px-5 py-2 rounded-full glass border border-brand-primary/20 text-brand-primary text-xs font-black uppercase tracking-[0.3em] mb-2">
        Jalur Komunikasi
      </span>
      <h1 id="contact-title" class="text-5xl md:text-7xl lg:text-8xl font-black text-main leading-tight md:leading-none tracking-tighter">
        TERHUBUNG <br class="md:hidden" /> <span class="text-stroke-primary">DENGAN KAMI</span>
      </h1>
      <p id="contact-subtitle" class="text-muted max-w-2xl mx-auto text-sm md:text-base tracking-[0.2em] uppercase font-semibold mt-8">
        Layanan eksklusif menanti Anda. Jadwalkan reservasi atau konsultasikan kebutuhan mesin Anda bersama tim ahli kami.
      </p>
    </div>
  </section>

  <!-- ===== SPLIT CONTACT SECTION ===== -->
  <section id="contact-section" class="py-16 md:py-24 bg-main relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-16 lg:gap-8 items-stretch">
      
      <!-- Info Left -->
      <div id="contact-info-container" class="lg:col-span-5 flex flex-col justify-center space-y-8 z-10">
        
        <div class="contact-card glass p-6 md:p-8 rounded-3xl border border-brand-primary/10 relative overflow-hidden group">
          <div class="absolute top-0 right-0 w-32 h-32 bg-brand-primary/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
          <div class="flex flex-col items-center justify-center text-center gap-4 md:gap-6 relative z-10 sm:flex-row sm:items-start sm:text-left">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-brand-surface-solid border border-brand-primary/20 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-primary group-hover:border-brand-primary transition-colors duration-500 shadow-lg">
              <i class="fa-solid fa-location-dot text-lg md:text-xl text-brand-primary group-hover:text-white transition-colors"></i>
            </div>
            <div>
              <h4 class="font-bold text-main text-lg tracking-wide mb-2 uppercase">Markas Presisi</h4>
              <p class="text-muted text-sm leading-relaxed">
                Jl. Karangmulya II No.74, Drajat,<br/>
                Kec. Kesambi, Kota Cirebon,<br/>
                Jawa Barat 45133
              </p>
            </div>
          </div>
        </div>

        <div class="contact-card glass p-6 md:p-8 rounded-3xl border border-brand-primary/10 relative overflow-hidden group">
          <div class="absolute top-0 right-0 w-32 h-32 bg-brand-whatsapp/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
          <div class="flex flex-col items-center justify-center text-center gap-4 md:gap-6 relative z-10 sm:flex-row sm:items-start sm:text-left">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-brand-surface-solid border border-brand-whatsapp/20 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-whatsapp group-hover:border-brand-whatsapp transition-colors duration-500 shadow-lg">
              <i class="fa-brands fa-whatsapp text-lg md:text-xl text-brand-whatsapp group-hover:text-white transition-colors"></i>
            </div>
            <div>
              <h4 class="font-bold text-main text-lg tracking-wide mb-2 uppercase">WhatsApp Concierge</h4>
              <p class="text-muted text-sm leading-relaxed mb-3">Tersedia untuk konsultasi cepat & reservasi antrean VIP.</p>
              <p class="font-black text-main text-xl tracking-wider">+62 812 3456 7890</p>
            </div>
          </div>
        </div>

        <div class="contact-card glass p-6 md:p-8 rounded-3xl border border-brand-primary/10 relative overflow-hidden group">
          <div class="absolute top-0 right-0 w-32 h-32 bg-brand-primary/10 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
          <div class="flex flex-col items-center justify-center text-center gap-4 md:gap-6 relative z-10 sm:flex-row sm:items-start sm:text-left">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-brand-surface-solid border border-brand-primary/20 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-primary group-hover:border-brand-primary transition-colors duration-500 shadow-lg">
              <i class="fa-solid fa-clock text-lg md:text-xl text-brand-primary group-hover:text-white transition-colors"></i>
            </div>
            <div>
              <h4 class="font-bold text-main text-lg tracking-wide mb-2 uppercase">Waktu Operasional</h4>
              <p class="text-muted text-sm leading-relaxed">
                Senin – Sabtu <br/>
                <span class="text-main font-bold mt-1 inline-block">08.00 – 17.00 WIB</span>
              </p>
            </div>
          </div>
        </div>

        <div id="contact-cta" class="pt-6">
          <a href="https://wa.me/6281234567890?text=Halo%2C+saya+ingin+konsultasi+servis+motor+premium" class="w-full inline-flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 bg-brand-primary text-white px-6 py-4 md:px-8 md:py-5 rounded-2xl font-bold uppercase tracking-widest hover:bg-white hover:text-brand-primary transition-all duration-500 shadow-[0_0_30px_rgba(179,50,50,0.3)] hover:shadow-[0_0_50px_rgba(255,255,255,0.4)] group overflow-hidden relative text-center text-xs md:text-sm">
            <div class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
            <i class="fa-brands fa-whatsapp text-2xl relative z-10"></i>
            <span class="relative z-10">Mulai Percakapan</span>
          </a>
        </div>
      </div>

      <!-- Map Right -->
      <div class="lg:col-span-7 relative map-wrap h-[400px] sm:h-[500px] lg:h-auto rounded-3xl md:rounded-[3rem] overflow-hidden border border-brand-primary/10 shadow-[0_0_50px_rgba(0,0,0,0.5)]">
        <!-- Overlay edge fade -->
        <div class="absolute inset-0 map-overlay z-10"></div>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.3130514616864!2d108.56277209999999!3d-6.731608199999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f1db56912eef9%3A0xbc0c8ed5508ad330!2sBengkel%20Agung%20Motor!5e0!3m2!1sen!2sid!4v1778422156649!5m2!1sen!2sid"
          class="absolute inset-0 w-full h-[120%] -top-[10%] map-frame"
          style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        
        <!-- Floating Glass Label -->
        <div class="absolute bottom-8 right-8 glass px-6 py-3 rounded-full border border-brand-primary/20 text-xs font-bold uppercase tracking-widest text-main shadow-2xl z-20 backdrop-blur-xl flex items-center gap-3">
          <div class="w-2 h-2 bg-brand-primary rounded-full animate-pulse shadow-[0_0_10px_rgba(179,50,50,1)]"></div>
          Agung Motor Studio
        </div>
      </div>

    </div>
  </section>

@endsection

@push('styles')
  @vite(['resources/css/contact.css'])
@endpush

@push('scripts')
  @vite(['resources/js/contact.js'])
@endpush