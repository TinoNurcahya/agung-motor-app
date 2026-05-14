{{-- ============================================================
     PARTIAL: partials/admin/topbar.blade.php
     Digunakan oleh: layouts/admin.blade.php
     ============================================================ --}}

<header
  class="h-16 shrink-0 bg-brand-surface/60 backdrop-blur-xl border-b border-brand-primary/10 flex items-center justify-between px-6 z-10">

  {{-- Mobile Hamburger --}}
  <button class="md:hidden text-gray-500 hover:text-white transition-colors" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars text-xl"></i>
  </button>

  {{-- Search Bar (Desktop) --}}
  <div class="hidden md:flex items-center gap-2 flex-1 max-w-md">
    <div class="relative w-full">
      <input type="text" id="globalSearch" 
        placeholder="Cari transaksi, produk, plat nomor, pemilik..."
        autocomplete="off"
        class="w-full bg-brand-surface border border-brand-primary/10 rounded-full px-4 py-2 pl-10 text-sm focus:ring-1 focus:ring-brand-primary focus:border-brand-primary outline-none">
      <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-muted text-xs"></i>
      
      {{-- Loading Indicator --}}
      <div id="searchLoading" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
        <i class="fa-solid fa-spinner fa-spin text-muted text-xs"></i>
      </div>
      
      {{-- Clear Button --}}
      <button id="clearSearch" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-brand-primary">
        <i class="fa-solid fa-times-circle text-xs"></i>
      </button>
    </div>
  </div>

  {{-- Right Side Actions --}}
  <div class="flex items-center gap-3">
    {{-- Web Notifications Toggle --}}
    <div class="relative">
      <button id="notification-toggle" onclick="window.toggleNotifications(event)"
        class="w-9 h-9 glass rounded-full flex items-center justify-center text-muted hover:text-brand-primary transition-colors relative cursor-pointer"
        title="Notifikasi Sistem">
        <i class="fa-solid fa-bell pointer-events-none"></i>
        <span id="notification-badge" class="hidden absolute top-1 right-1 w-2 h-2 bg-brand-income rounded-full animate-ping pointer-events-none"></span>
        <span id="notification-badge-dot" class="hidden absolute top-1 right-1 w-2 h-2 bg-brand-income rounded-full pointer-events-none"></span>
      </button>
    </div>

    {{-- Theme Toggle --}}
    <button id="theme-toggle"
      class="w-9 h-9 glass rounded-full flex items-center justify-center text-muted hover:text-brand-primary transition-colors"
      title="Toggle Theme">
      <i class="fa-solid fa-moon" id="theme-icon-dark"></i>
      <i class="fa-solid fa-sun hidden" id="theme-icon-light"></i>
    </button>
  </div>

</header>

{{-- Search Results Dropdown --}}
<div id="searchDropdown" 
  class="hidden fixed top-16 left-1/2 transform -translate-x-1/2 w-full max-w-3xl z-50 mt-2 px-4">
  <div class="glass-card rounded-xl shadow-2xl border border-brand-primary/20 overflow-hidden max-h-[80vh] overflow-y-auto">
    <div class="p-3 border-b border-brand-primary/10 bg-brand-primary/5 sticky top-0">
      <div class="flex items-center justify-between">
        <p class="text-xs font-bold text-muted uppercase tracking-wider">
          <span id="searchResultCount">0</span> hasil ditemukan untuk "<span id="searchQueryDisplay"></span>"
        </p>
        <div class="flex gap-2">
          <button id="closeSearchDropdown" class="text-muted hover:text-brand-primary text-xs">
            <i class="fa-solid fa-times"></i> ESC
          </button>
        </div>
      </div>
    </div>
    
    <div id="searchResults" class="divide-y divide-brand-primary/10">
      {{-- Results will be populated here --}}
    </div>
    
    <div class="p-3 border-t border-brand-primary/10 bg-brand-primary/5 sticky bottom-0">
      <a href="#" id="viewAllResults" class="text-xs text-brand-primary hover:underline flex items-center justify-center gap-1">
      </a>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let topbarSearchTimeout;
  const searchInput = document.getElementById('globalSearch');
  const searchDropdown = document.getElementById('searchDropdown');
  const searchLoading = document.getElementById('searchLoading');
  const clearSearch = document.getElementById('clearSearch');
  const searchResults = document.getElementById('searchResults');
  const searchResultCount = document.getElementById('searchResultCount');
  const viewAllResults = document.getElementById('viewAllResults');
  
  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
      searchDropdown.classList.add('hidden');
    }
  });
  
  // Search input handler
  searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    if (query.length > 0) {
      clearSearch.classList.remove('hidden');
    } else {
      clearSearch.classList.add('hidden');
      searchDropdown.classList.add('hidden');
      return;
    }
    
    // Clear previous timeout
    clearTimeout(topbarSearchTimeout);
    
    // Set timeout untuk debounce (500ms)
    topbarSearchTimeout = setTimeout(() => {
      performSearch(query);
    }, 500);
  });
  
  // Clear search button
  clearSearch.addEventListener('click', function() {
    searchInput.value = '';
    clearSearch.classList.add('hidden');
    searchDropdown.classList.add('hidden');
    searchInput.focus();
  });
  
  // Perform search API call
  function performSearch(query) {
    searchLoading.classList.remove('hidden');
    searchDropdown.classList.remove('hidden');
    document.getElementById('searchQueryDisplay').textContent = query;
    searchResults.innerHTML = '<div class="p-6 text-center text-muted"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Mencari...</div>';
    
    fetch(`{{ route('admin.search.api') }}?q=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        searchLoading.classList.add('hidden');
        searchResultCount.textContent = data.total;
        
        if (data.total === 0) {
          searchResults.innerHTML = `
            <div class="p-6 text-center text-muted">
              <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
              Tidak ada hasil untuk "${escapeHtml(query)}"
            </div>
            <div class="p-4 bg-brand-primary/5">
              <p class="text-xs text-muted text-center">Coba kata kunci lain seperti: plat nomor, nama pemilik, produk, atau menu</p>
            </div>
          `;
          viewAllResults.href = '#';
          viewAllResults.style.pointerEvents = 'none';
          viewAllResults.style.opacity = '0.5';
          return;
        }
        
        viewAllResults.href = `{{ route('admin.search') }}?q=${encodeURIComponent(query)}`;
        viewAllResults.style.pointerEvents = 'auto';
        viewAllResults.style.opacity = '1';
        
        let html = '';
        
        // Menu Results (Quick Access)
        if (data.grouped.menu && data.grouped.menu.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-brand-primary">
                <i class="fa-solid fa-bolt mr-1"></i> Akses Cepat
              </p>
            </div>
          `;
          data.grouped.menu.forEach(item => {
            html += renderResultItem(item);
          });
        }
        
        // Penghasilan Results
        if (data.grouped.penghasilan && data.grouped.penghasilan.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-brand-income">
                <i class="fa-solid fa-money-bill-wave mr-1"></i> Penghasilan (${data.grouped.penghasilan.length})
              </p>
            </div>
          `;
          data.grouped.penghasilan.slice(0, 5).forEach(item => {
            html += renderResultItem(item);
          });
          if (data.grouped.penghasilan.length > 5) {
            html += `<div class="p-2 text-center"><a href="{{ route('admin.search') }}?q=${encodeURIComponent(query)}#penghasilan" class="text-[10px] text-muted hover:text-brand-primary">+ ${data.grouped.penghasilan.length - 5} hasil lainnya</a></div>`;
          }
        }
        
        // Pengeluaran Results
        if (data.grouped.pengeluaran && data.grouped.pengeluaran.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-brand-expense">
                <i class="fa-solid fa-receipt mr-1"></i> Pengeluaran (${data.grouped.pengeluaran.length})
              </p>
            </div>
          `;
          data.grouped.pengeluaran.slice(0, 5).forEach(item => {
            html += renderResultItem(item);
          });
          if (data.grouped.pengeluaran.length > 5) {
            html += `<div class="p-2 text-center"><a href="{{ route('admin.search') }}?q=${encodeURIComponent(query)}#pengeluaran" class="text-[10px] text-muted hover:text-brand-primary">+ ${data.grouped.pengeluaran.length - 5} hasil lainnya</a></div>`;
          }
        }
        
        // Produk Results
        if (data.grouped.produk && data.grouped.produk.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-500">
                <i class="fa-solid fa-box mr-1"></i> Produk (${data.grouped.produk.length})
              </p>
            </div>
          `;
          data.grouped.produk.slice(0, 5).forEach(item => {
            html += renderResultItem(item);
          });
          if (data.grouped.produk.length > 5) {
            html += `<div class="p-2 text-center"><a href="{{ route('admin.search') }}?q=${encodeURIComponent(query)}#produk" class="text-[10px] text-muted hover:text-brand-primary">+ ${data.grouped.produk.length - 5} hasil lainnya</a></div>`;
          }
        }
        
        // Statistik Results
        if (data.grouped.statistik && data.grouped.statistik.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-green-500">
                <i class="fa-solid fa-chart-line mr-1"></i> Statistik
              </p>
            </div>
          `;
          data.grouped.statistik.forEach(item => {
            html += renderResultItem(item);
          });
        }
        
        // AI Insights Results
        if (data.grouped.ai_insight && data.grouped.ai_insight.length > 0) {
          html += `
            <div class="p-3 bg-brand-primary/5 sticky top-0 border-b border-brand-primary/10">
              <p class="text-[10px] font-bold uppercase tracking-wider text-cyan-500">
                <i class="fa-solid fa-microchip mr-1"></i> AI Insights
              </p>
            </div>
          `;
          data.grouped.ai_insight.forEach(item => {
            html += renderResultItem(item);
          });
        }
        
        searchResults.innerHTML = html;
      })
      .catch(error => {
        console.error('Search error:', error);
        searchLoading.classList.add('hidden');
        searchResults.innerHTML = `
          <div class="p-6 text-center text-red-500">
            <i class="fa-solid fa-exclamation-triangle text-2xl mb-2 block"></i>
            Terjadi kesalahan saat mencari
          </div>
        `;
      });
  }

  
  function renderResultItem(item) {
    let badgeColor = '';
    if (item.type === 'penghasilan') badgeColor = 'bg-brand-income/10 text-brand-income';
    else if (item.type === 'pengeluaran') badgeColor = 'bg-brand-expense/10 text-brand-expense';
    else badgeColor = 'bg-cyan-500/10 text-cyan-500';
    
    return `
      <a href="${item.url}" class="flex items-center justify-between p-4 hover:bg-brand-primary/5 transition-colors group">
        <div class="flex items-center gap-3 flex-1 min-w-0">
          <div class="w-10 h-10 rounded-lg bg-brand-surface border border-brand-primary/10 flex items-center justify-center ${item.icon_color} group-hover:scale-110 transition-transform">
            <i class="fa-solid ${item.icon} text-sm"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs font-bold text-muted">${item.type_label}</span>
              ${item.date ? `<span class="text-[10px] text-muted">${item.date}</span>` : ''}
            </div>
            <p class="text-sm font-semibold truncate">${escapeHtml(item.title)}</p>
            <p class="text-[10px] text-muted truncate">${escapeHtml(item.subtitle)}</p>
          </div>
        </div>
        <div class="text-right shrink-0 ml-3">
          <p class="text-sm font-bold ${item.type === 'penghasilan' ? 'text-brand-income' : (item.type === 'pengeluaran' ? 'text-brand-expense' : 'text-cyan-500')}">
            ${escapeHtml(item.value)}
          </p>
          <span class="text-[10px] text-muted inline-block mt-1 px-2 py-0.5 rounded-full ${badgeColor}">
            <i class="fa-solid fa-arrow-right text-[8px] mr-1"></i> Detail
          </span>
        </div>
      </a>
    `;
  }
  
  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }
  
  // Keyboard navigation
    document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      searchDropdown.classList.add('hidden');
      searchInput.blur();
    }
  });
  
  document.getElementById('closeSearchDropdown')?.addEventListener('click', function() {
    searchDropdown.classList.add('hidden');
  });

  // Notifications logic
  let seenNotifsCount = 0;

  window.toggleNotifications = function(e) {
    if (e) e.stopPropagation();
    const notifDropdown = document.getElementById('notification-dropdown');
    const notifBadge = document.getElementById('notification-badge');
    const notifBadgeDot = document.getElementById('notification-badge-dot');
    if (notifDropdown) {
      notifDropdown.classList.toggle('hidden');
      if (!notifDropdown.classList.contains('hidden')) {
        if (notifBadge) notifBadge.classList.add('hidden');
        if (notifBadgeDot) notifBadgeDot.classList.add('hidden');
        loadNotifications();
      }
    }
  };

  function loadNotifications() {
    const notifList = document.getElementById('notification-list');
    const notifBadge = document.getElementById('notification-badge');
    const notifBadgeDot = document.getElementById('notification-badge-dot');
    
    fetch("{{ route('admin.notifications') }}")
      .then(res => res.json())
      .then(data => {
        if (data.success && data.data) {
          const items = data.data;
          if (items.length > seenNotifsCount && seenNotifsCount !== 0) {
            if (notifBadge) notifBadge.classList.remove('hidden');
            if (notifBadgeDot) notifBadgeDot.classList.remove('hidden');
          }
          seenNotifsCount = items.length;
          
          if (!notifList) return;
          
          if (items.length === 0) {
            notifList.innerHTML = '<div class="p-6 text-center text-muted"><i class="fa-solid fa-inbox mb-2 text-xl block"></i>Belum ada notifikasi</div>';
            return;
          }

          let html = '';
          items.forEach(item => {
            let color = item.type === 'success' ? 'text-brand-income bg-brand-income/10 border-brand-income/20' : 
                        (item.type === 'info' ? 'text-cyan-500 bg-cyan-500/10 border-cyan-500/20' : 'text-red-500 bg-red-500/10 border-red-500/20');
            let icon = item.type === 'success' ? 'fa-money-bill-wave' : (item.type === 'info' ? 'fa-receipt' : 'fa-triangle-exclamation');
            let badgeText = item.type === 'success' ? 'Pemasukan' : (item.type === 'info' ? 'Pengeluaran' : 'Peringatan Stok');
            let badgeBg = item.type === 'success' ? 'bg-brand-income/10 text-brand-income border-brand-income/20' : 
                          (item.type === 'info' ? 'bg-cyan-500/10 text-cyan-500 border-cyan-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20');
            let clickAction = item.url ? `onclick="window.location.href='${item.url}'"` : '';
            
            html += `
              <div ${clickAction} class="p-4 hover:bg-brand-primary/5 transition-all flex gap-3 items-start border-b border-brand-primary/10 last:border-0 group cursor-pointer">
                <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center border ${color} group-hover:scale-110 transition-transform shadow-sm">
                  <i class="fa-solid ${icon} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between gap-2 mb-1">
                    <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border ${badgeBg}">${badgeText}</span>
                    <span class="text-[10px] font-medium text-muted shrink-0 flex items-center gap-1">
                      <i class="fa-regular fa-clock text-[9px]"></i> ${item.time}
                    </span>
                  </div>
                  <p class="text-xs font-bold text-main mb-0.5 group-hover:text-brand-primary transition-colors">${escapeHtml(item.title)}</p>
                  <p class="text-[11px] text-muted line-clamp-2 leading-relaxed">${escapeHtml(item.message)}</p>
                </div>
              </div>
            `;
          });
          notifList.innerHTML = html;
        }
      })
      .catch(() => {});
  }

  document.addEventListener('click', function(e) {
    const notifToggle = document.getElementById('notification-toggle');
    const notifDropdown = document.getElementById('notification-dropdown');
    if (notifToggle && notifDropdown && !notifToggle.contains(e.target) && !notifDropdown.contains(e.target)) {
      notifDropdown.classList.add('hidden');
    }
  });

  // Load initial notifications on page load and poll every 10 seconds
  loadNotifications();
  setInterval(loadNotifications, 10000);
</script>
@endpush