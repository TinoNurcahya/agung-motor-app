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
  let searchTimeout;
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
    clearTimeout(searchTimeout);
    
    // Set timeout untuk debounce (500ms)
    searchTimeout = setTimeout(() => {
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
</script>
@endpush