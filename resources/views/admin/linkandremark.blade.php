{{-- resources/views/admin/linkandremark.blade.php --}}
@extends('components.layout')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Links Manager | Digital Marketing CRM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- QR Code lib -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        whatsapp: '#25D366',
                        instagram: '#E1306C',
                        facebook: '#1877F2',
                        linkedin: '#0A66C2',
                        youtube: '#FF0000'
                    },
                    screens: {
                        'xs': '475px',
                        '3xl': '1920px',
                    }
                }
            }
        }
    </script>

    <style>
        .modal-overlay { 
            background: rgba(0,0,0,0.5); 
            padding: 1rem;
            align-items: flex-start;
        }
        @media (min-width: 640px) {
            .modal-overlay {
                align-items: center;
                padding: 2rem;
            }
        }
        
        .platform-icon { 
            width:2.5rem; 
            height:2.5rem; 
            border-radius:0.5rem; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            color:white;
        }
        @media (max-width: 640px) {
            .platform-icon {
                width: 2rem;
                height: 2rem;
            }
        }
        
        .pointer { cursor: pointer }
        .active-filter { background-color: #4f46e5; color: white }
        
        /* Touch-friendly buttons */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 2px;
        }
        
        /* Animation for mobile */
        .slide-up {
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        
        /* Responsive grid adjustments */
        @media (max-width: 768px) {
            .mobile-stack {
                flex-direction: column;
            }
            .mobile-full-width {
                width: 100%;
            }
            .mobile-hide {
                display: none !important;
            }
            .mobile-show {
                display: block !important;
            }
        }
        
        /* Responsive table/card layout */
        @media (max-width: 1024px) {
            .tablet-adjust {
                flex-direction: column;
            }
            .tablet-full {
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto p-3 sm:p-4 lg:p-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 sm:mb-5 lg:mb-6">
            <div class="mb-3 sm:mb-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Links & Social Manager</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Manage social and website links, analytics, QR codes and more.</p>
            </div>
            
            <!-- Search and Controls -->
            <div class="flex flex-col xs:flex-row gap-2 sm:gap-3 w-full md:w-auto">
                <!-- Search Input -->
                <div class="relative w-full xs:w-auto">
                    <input id="searchInput" type="search" placeholder="Search title, url or type"
                        class="touch-target border px-3 sm:px-4 py-2 rounded-lg w-full xs:w-48 sm:w-64 text-sm sm:text-base" />
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <!-- Sort Select -->
                <select id="sortSelect" class="touch-target border px-3 sm:px-4 py-2 rounded-lg w-full xs:w-auto text-sm sm:text-base">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="clicks_desc">Clicks (High → Low)</option>
                    <option value="clicks_asc">Clicks (Low → High)</option>
                    <option value="title_asc">Title (A → Z)</option>
                    <option value="title_desc">Title (Z → A)</option>
                </select>
                
                <!-- Add Button -->
                <button id="addSocialLink" class="touch-target bg-indigo-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center justify-center text-sm sm:text-base whitespace-nowrap">
                    <i class="fas fa-plus mr-1.5 sm:mr-2 text-sm sm:text-base"></i>
                    <span class="hidden xs:inline">Add Link</span>
                    <span class="xs:hidden">Add</span>
                </button>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            <!-- List + Filters (Left Column) -->
            <div class="lg:col-span-2 bg-white p-4 sm:p-5 lg:p-6 rounded-lg sm:rounded-xl shadow">
                <!-- Filter Controls -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 sm:mb-4 space-y-3 sm:space-y-0">
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <!-- Filter Buttons -->
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            <button id="filterAll" class="filter-btn touch-target px-2.5 sm:px-3 py-1.5 sm:py-1 rounded-lg active-filter text-xs sm:text-sm whitespace-nowrap">All</button>
                            <button id="filterSocial" class="filter-btn touch-target px-2.5 sm:px-3 py-1.5 sm:py-1 rounded-lg text-xs sm:text-sm whitespace-nowrap">Social</button>
                            <button id="filterWebsite" class="filter-btn touch-target px-2.5 sm:px-3 py-1.5 sm:py-1 rounded-lg text-xs sm:text-sm whitespace-nowrap">Websites</button>
                        </div>
                        
                        <!-- Category Select -->
                        <select id="categorySelect" class="touch-target border px-2 sm:px-3 py-1.5 sm:py-1 rounded-lg text-xs sm:text-sm w-full sm:w-auto mt-1.5 sm:mt-0">
                            <option value="">All categories</option>
                            <option value="marketing">Marketing</option>
                            <option value="support">Support</option>
                            <option value="internal">Internal</option>
                        </select>
                    </div>

                    <!-- Counter -->
                    <div class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">
                        Showing <span id="visibleCount" class="font-medium">0</span> of <span id="totalCount" class="font-medium">0</span>
                    </div>
                </div>

                <!-- Links List -->
                <div id="linksList" class="space-y-2 sm:space-y-3 max-h-[50vh] sm:max-h-[60vh] overflow-y-auto custom-scrollbar p-1"></div>

                <!-- Pagination -->
                <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-0">
                    <div class="flex gap-2">
                        <button id="prevPage" class="touch-target px-3 py-1.5 sm:py-1 border rounded-lg text-xs sm:text-sm w-20 sm:w-auto">Prev</button>
                        <button id="nextPage" class="touch-target px-3 py-1.5 sm:py-1 border rounded-lg text-xs sm:text-sm w-20 sm:w-auto">Next</button>
                    </div>
                    <div class="text-xs sm:text-sm text-gray-500">
                        Page <span id="currentPage" class="font-medium">1</span> / <span id="totalPages" class="font-medium">1</span>
                    </div>
                </div>
            </div>

            <!-- Details & Analytics (Right Column) -->
            <div class="bg-white p-4 sm:p-5 lg:p-6 rounded-lg sm:rounded-xl shadow">
                <!-- Empty State -->
                <div id="linkDetails" class="mb-4 text-center py-8 sm:py-12">
                    <i class="fas fa-mouse-pointer text-4xl sm:text-5xl text-gray-300 mb-3 sm:mb-4"></i>
                    <p class="text-sm sm:text-base text-gray-500">Select a link to view details</p>
                </div>

                <!-- Analytics Content (Hidden by default) -->
                <div id="analyticsSummary" class="hidden">
                    <!-- Analytics Title -->
                    <h4 class="text-base sm:text-lg lg:text-xl font-semibold mb-3 sm:mb-4">Analytics</h4>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3 sm:mb-4">
                        <div class="bg-gray-50 p-2 sm:p-3 rounded-lg text-center">
                            <div class="text-xs sm:text-sm text-gray-500">Clicks</div>
                            <div class="text-xl sm:text-2xl font-bold" id="clicksCount">0</div>
                        </div>
                        <div class="bg-gray-50 p-2 sm:p-3 rounded-lg text-center">
                            <div class="text-xs sm:text-sm text-gray-500">Engagement</div>
                            <div class="text-xl sm:text-2xl font-bold" id="engagementRate">0%</div>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="mb-3 sm:mb-4">
                        <h5 class="font-semibold mb-1.5 sm:mb-2 text-sm sm:text-base">QR Code</h5>
                        <div id="qrWrap" class="flex items-center justify-center p-2 sm:p-3 border rounded-lg"></div>
                        <div class="flex flex-col xs:flex-row gap-2 xs:gap-0 xs:justify-between mt-2 sm:mt-3">
                            <button id="downloadQR" class="touch-target px-3 py-1.5 sm:py-1 bg-indigo-600 text-white rounded-lg text-xs sm:text-sm whitespace-nowrap">
                                <i class="fas fa-download mr-1.5"></i>
                                Download QR
                            </button>
                            <button id="openLinkBtn" class="touch-target px-3 py-1.5 sm:py-1 bg-green-600 text-white rounded-lg text-xs sm:text-sm whitespace-nowrap">
                                <i class="fas fa-external-link-alt mr-1.5"></i>
                                Open Link
                            </button>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div>
                        <h5 class="font-semibold mb-1.5 sm:mb-2 text-sm sm:text-base">Activity Log (session)</h5>
                        <div id="activityLog" class="text-xs text-gray-600 max-h-32 sm:max-h-40 overflow-y-auto p-2 border rounded-lg custom-scrollbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="linkModal" class="fixed inset-0 z-50 hidden modal-overlay overflow-y-auto custom-scrollbar">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg w-full max-w-2xl mx-auto my-2 sm:my-0 fade-in slide-up">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 sm:p-5 lg:p-6 border-b">
                <h3 id="modalTitle" class="text-base sm:text-lg lg:text-xl font-bold text-gray-800">Add Link</h3>
                <button id="closeLinkModal" class="touch-target text-gray-500 hover:text-gray-700 active:text-gray-900">
                    <i class="fas fa-times text-lg sm:text-xl"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="linkForm" class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4 p-4 sm:p-5 lg:p-6">
                <input type="hidden" id="editLinkId">

                <!-- Type Field -->
                <div>
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">Type</label>
                    <select id="linkType" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base">
                        <option value="website">Website</option>
                        <option value="instagram">Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="youtube">YouTube</option>
                    </select>
                </div>

                <!-- Category Field -->
                <div>
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">Category</label>
                    <select id="linkCategory" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base">
                        <option value="marketing">Marketing</option>
                        <option value="support">Support</option>
                        <option value="internal">Internal</option>
                    </select>
                </div>

                <!-- Title Field (Full Width) -->
                <div class="lg:col-span-2">
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">Title / Username</label>
                    <input id="titleInput" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base" />
                </div>

                <!-- URL Field (Full Width) -->  
                <div class="lg:col-span-2">
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">URL</label>
                    <input id="urlInput" type="url" placeholder="https://example.com or example.com (https:// will be added automatically)" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base" />
                </div>

                <!-- Note Field (Full Width) -->
                <div class="lg:col-span-2">
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">Note</label>
                    <textarea id="noteInput" rows="3" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base"></textarea>
                </div>

                <!-- Status Field -->
                <div>
                    <label class="text-xs sm:text-sm text-gray-700 mb-1 block">Status</label>
                    <select id="statusInput" class="touch-target w-full border px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Buttons (Full Width) -->
                <div class="flex items-center justify-end gap-2 sm:gap-3 lg:col-span-2 mt-2 sm:mt-4">
                    <button id="cancelLink" type="button" class="touch-target px-3 sm:px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400 text-sm sm:text-base whitespace-nowrap">
                        Cancel
                    </button>
                    <button type="submit" class="touch-target px-3 sm:px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 text-sm sm:text-base whitespace-nowrap">
                        Save Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ---------- state ----------
        let links = []; // full dataset fetched from server
        let filtered = []; // filtered + sorted dataset
        let pageSize = 8;
        let currentPage = 1;
        let totalPages = 1;
        let currentFilter = 'all';
        let currentCategory = '';
        let currentSort = 'newest';
        let activeLink = null; // selected link object

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Elements
        const linksListEl = document.getElementById('linksList');
        const totalCountEl = document.getElementById('totalCount');
        const visibleCountEl = document.getElementById('visibleCount');
        const currentPageEl = document.getElementById('currentPage');
        const totalPagesEl = document.getElementById('totalPages');
        const clicksCountEl = document.getElementById('clicksCount');
        const engagementRateEl = document.getElementById('engagementRate');
        const analyticsSummaryEl = document.getElementById('analyticsSummary');
        const linkDetailsEl = document.getElementById('linkDetails');
        const qrWrap = document.getElementById('qrWrap');
        const activityLogEl = document.getElementById('activityLog');

        // INPUTS
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const filterAll = document.getElementById('filterAll');
        const filterSocial = document.getElementById('filterSocial');
        const filterWebsite = document.getElementById('filterWebsite');
        const categorySelect = document.getElementById('categorySelect');

        // Pagination
        document.getElementById('prevPage').onclick = () => { 
            if (currentPage > 1) { 
                currentPage--; 
                renderPage(); 
            } 
        };
        document.getElementById('nextPage').onclick = () => { 
            if (currentPage < totalPages) { 
                currentPage++; 
                renderPage(); 
            } 
        };

        // Modal controls
        document.getElementById('addSocialLink').onclick = () => openLinkModal();
        document.getElementById('closeLinkModal').onclick = closeModal;
        document.getElementById('cancelLink').onclick = closeModal;

        // Filters
        filterAll.onclick = () => { 
            currentFilter='all'; 
            updateFilterButtons(); 
            applyFilters(); 
        };
        filterSocial.onclick = () => { 
            currentFilter='social'; 
            updateFilterButtons(); 
            applyFilters(); 
        };
        filterWebsite.onclick = () => { 
            currentFilter='website'; 
            updateFilterButtons(); 
            applyFilters(); 
        };
        categorySelect.onchange = (e) => { 
            currentCategory = e.target.value; 
            applyFilters(); 
        };

        // Debounced search
        searchInput.oninput = debounce(() => { 
            applyFilters(); 
        }, 300);
        
        sortSelect.onchange = (e) => { 
            currentSort = e.target.value; 
            applyFilters(); 
        };

        function updateFilterButtons(){
            [filterAll, filterSocial, filterWebsite].forEach(b => { 
                b.classList.remove('active-filter'); 
            });
            if(currentFilter === 'all') filterAll.classList.add('active-filter');
            if(currentFilter === 'social') filterSocial.classList.add('active-filter');
            if(currentFilter === 'website') filterWebsite.classList.add('active-filter');
        }

        // ------------------- load -------------------
        async function loadLinks(){
            try{
                const res = await fetch('/links/fetch-all');
                links = await res.json();
                // ensure numeric fields
                links.forEach(l => { 
                    l.clicks = Number(l.clicks) || 0; 
                    l.engagement = Number(l.engagement) || 0; 
                    l.category = l.category || 'marketing'; 
                });
                totalCountEl.textContent = links.length;
                applyFilters();
                logActivity('Fetched links from server');
            }catch(err){
                console.error(err);
                alert('Failed to load links');
            }
        }

        // ------------------- filtering, sorting, pagination -------------------
        function applyFilters(){
            const q = searchInput.value.trim().toLowerCase();

            filtered = links.filter(l => {
                if(currentFilter === 'social' && l.type === 'website') return false;
                if(currentFilter === 'website' && l.type !== 'website') return false;
                if(currentCategory && l.category !== currentCategory) return false;
                if(!q) return true;
                return (l.title||'').toLowerCase().includes(q) || 
                       (l.url||'').toLowerCase().includes(q) || 
                       (l.type||'').toLowerCase().includes(q);
            });

            sortFiltered();

            totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
            if(currentPage > totalPages) currentPage = totalPages;
            renderPage();
        }

        function sortFiltered(){
            filtered.sort((a,b) => {
                switch(currentSort){
                    case 'oldest': 
                        return new Date(a.created_at) - new Date(b.created_at);
                    case 'clicks_desc': 
                        return (b.clicks||0) - (a.clicks||0);
                    case 'clicks_asc': 
                        return (a.clicks||0) - (b.clicks||0);
                    case 'title_asc': 
                        return (a.title||'').localeCompare(b.title||'');
                    case 'title_desc': 
                        return (b.title||'').localeCompare(a.title||'');
                    case 'newest':
                    default:
                        return new Date(b.created_at) - new Date(a.created_at);
                }
            });
        }

        function renderPage(){
            const start = (currentPage-1) * pageSize;
            const pageData = filtered.slice(start, start + pageSize);

            linksListEl.innerHTML = '';
            if(pageData.length === 0){ 
                linksListEl.innerHTML = '<div class="text-center py-8 text-gray-500 text-sm sm:text-base">No links found</div>'; 
            }

            pageData.forEach(link => {
                const el = document.createElement('div');
                el.className = 'p-2 sm:p-3 border rounded-lg flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0';
                el.style.cursor = 'pointer';

                // Platform icon color based on type
                let platformColor = 'bg-indigo-600';
                let platformIcon = 'fas fa-globe';
                switch(link.type) {
                    case 'instagram': platformColor = 'bg-instagram'; platformIcon = 'fab fa-instagram'; break;
                    case 'facebook': platformColor = 'bg-facebook'; platformIcon = 'fab fa-facebook'; break;
                    case 'whatsapp': platformColor = 'bg-whatsapp'; platformIcon = 'fab fa-whatsapp'; break;
                    case 'linkedin': platformColor = 'bg-linkedin'; platformIcon = 'fab fa-linkedin'; break;
                    case 'youtube': platformColor = 'bg-youtube'; platformIcon = 'fab fa-youtube'; break;
                    default: platformColor = 'bg-indigo-600'; platformIcon = 'fas fa-globe';
                }

                // Determine if we should show full or truncated URL based on screen size
                const displayUrl = window.innerWidth <= 768 ? 
                    truncateText(link.url, 40) : 
                    truncateText(link.url, 60);

                el.innerHTML = `
                    <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                        <div class="platform-icon ${platformColor}">
                            <i class="${platformIcon} text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-semibold text-sm sm:text-base truncate">${escapeHtml(link.title)}</div>
                            <a href="${escapeHtml(link.url)}" target="_blank" 
                               class="text-xs sm:text-sm text-indigo-600 truncate block hover:underline"
                               onclick="trackAndOpen(${link.id}, event)">
                               ${escapeHtml(displayUrl)}
                            </a>
                            <div class="text-xs text-gray-500 truncate">${escapeHtml(link.note || '')}</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end space-x-1 sm:space-x-2 mt-1 sm:mt-0">
                        <div class="text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full ${link.status==='active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'} whitespace-nowrap">
                            ${link.status}
                        </div>
                        <div class="text-xs text-gray-600 whitespace-nowrap hidden xs:block">
                            ${link.clicks || 0} clicks
                        </div>
                        <div class="flex items-center space-x-1">
                            <button class="touch-target p-1 sm:p-1.5 rounded hover:bg-gray-100 active:bg-gray-200" 
                                    onclick="event.stopPropagation(); copyToClipboard('${escapeJs(link.url)}')"
                                    title="Copy URL">
                                <i class="fas fa-copy text-xs sm:text-sm"></i>
                            </button>
                            <button class="touch-target p-1 sm:p-1.5 rounded hover:bg-gray-100 active:bg-gray-200" 
                                    onclick="event.stopPropagation(); openLinkModal(${link.id})"
                                    title="Edit">
                                <i class="fas fa-edit text-xs sm:text-sm text-indigo-600"></i>
                            </button>
                            <button class="touch-target p-1 sm:p-1.5 rounded hover:bg-gray-100 active:bg-gray-200" 
                                    onclick="event.stopPropagation(); deleteLink(${link.id})"
                                    title="Delete">
                                <i class="fas fa-trash text-xs sm:text-sm text-red-600"></i>
                            </button>
                        </div>
                    </div>
                `;

                el.onclick = () => selectLink(link.id);
                linksListEl.appendChild(el);
            });

            visibleCountEl.textContent = filtered.length;
            currentPageEl.textContent = currentPage;
            totalPagesEl.textContent = totalPages;
        }

        // ------------------- utilities -------------------
        function escapeHtml(s){ 
            if(!s) return ''; 
            return String(s).replace(/[&<>"]+/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;' }[c]||c)); 
        }
        
        function escapeJs(s){ 
            if(!s) return ''; 
            return String(s).replace(/'/g, "\\'").replace(/"/g, '\\"'); 
        }
        
        function truncateText(text, maxLength) {
            if (!text) return '';
            if (text.length <= maxLength) return text;
            return text.substring(0, maxLength) + '...';
        }

        function debounce(fn, wait){ 
            let t; 
            return function(...a){ 
                clearTimeout(t); 
                t = setTimeout(() => fn.apply(this, a), wait); 
            } 
        }

        // ------------------- modal / form -------------------
        function openLinkModal(id = null){
            document.getElementById('linkModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = id ? 'Edit Link' : 'Add Link';
            
            // Adjust modal position for mobile
            if (window.innerWidth <= 640) {
                document.getElementById('linkModal').classList.remove('items-center');
                document.getElementById('linkModal').classList.add('items-start');
            }
            
            if(id){
                const link = links.find(l => l.id === id);
                document.getElementById('editLinkId').value = id;
                document.getElementById('linkType').value = link.type;
                document.getElementById('linkCategory').value = link.category || 'marketing';
                document.getElementById('titleInput').value = link.title;
                document.getElementById('urlInput').value = link.url;
                document.getElementById('noteInput').value = link.note || '';
                document.getElementById('statusInput').value = link.status || 'active';
            }else{
                document.getElementById('linkForm').reset();
                document.getElementById('editLinkId').value = '';
            }
        }

        function closeModal(){ 
            document.getElementById('linkModal').classList.add('hidden'); 
            // Restore body scroll on mobile
            if (window.innerWidth <= 768) {
                document.body.style.position = '';
                document.body.style.width = '';
            }
        }


        // Update the URL input field to add https:// automatically
document.getElementById('urlInput').addEventListener('blur', function() {
    let url = this.value.trim();
    if (url) {
        // Check if it doesn't start with http:// or https://
        if (!url.match(/^https?:\/\//)) {
            // Check if it looks like a domain (contains .)
            if (url.includes('.') && !url.includes(' ')) {
                this.value = 'https://' + url;
            }
        }
    }
});

// Also add validation when form is submitted
document.getElementById('linkForm').onsubmit = async function(e) {
    e.preventDefault();
    
    // Validate and fix URL before submission
    let urlInput = document.getElementById('urlInput');
    let url = urlInput.value.trim();
    
    if (url) {
        // Check if URL doesn't start with http:// or https://
        if (!url.match(/^https?:\/\//)) {
            // Add https:// if it looks like a domain
            if (url.includes('.') && !url.includes(' ')) {
                url = 'https://' + url;
                urlInput.value = url;
            } else {
                alert('Please enter a valid URL (e.g., example.com or https://example.com)');
                return;
            }
        }
        
        // Additional validation for social media URLs
        const linkType = document.getElementById('linkType').value;
        if (linkType !== 'website') {
            // For social media, ensure proper format
            if (linkType === 'instagram' && !url.includes('instagram.com')) {
                url = 'https://instagram.com/' + url.replace(/^@/, '');
                urlInput.value = url;
            } else if (linkType === 'facebook' && !url.includes('facebook.com')) {
                url = 'https://facebook.com/' + url;
                urlInput.value = url;
            } else if (linkType === 'whatsapp' && !url.match(/^https?:\/\/wa\.me\/|^https?:\/\/api\.whatsapp\.com\//)) {
                // Clean phone number
                let phone = url.replace(/[^0-9]/g, '');
                if (phone) {
                    url = 'https://wa.me/' + phone;
                    urlInput.value = url;
                }
            } else if (linkType === 'linkedin' && !url.includes('linkedin.com')) {
                if (url.includes('/in/')) {
                    url = 'https://linkedin.com' + (url.startsWith('/') ? '' : '/') + url;
                } else {
                    url = 'https://linkedin.com/in/' + url;
                }
                urlInput.value = url;
            } else if (linkType === 'youtube' && !url.includes('youtube.com')) {
                if (url.includes('/channel/') || url.includes('/c/') || url.includes('/user/')) {
                    url = 'https://youtube.com' + (url.startsWith('/') ? '' : '/') + url;
                } else {
                    url = 'https://youtube.com/c/' + url;
                }
                urlInput.value = url;
            }
        }
    }
    
    // Rest of your existing form submission code...
    const id = document.getElementById('editLinkId').value;
    const payload = {
        type: document.getElementById('linkType').value,
        title: document.getElementById('titleInput').value,
        url: urlInput.value, // Use the corrected URL
        note: document.getElementById('noteInput').value,
        status: document.getElementById('statusInput').value,
        category: document.getElementById('linkCategory').value
    };
    
    try {
        const url = id ? `/links/update/${id}` : '/links/store';
        const method = id ? 'PUT' : 'POST';
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(payload)
        });
        if(!res.ok) throw new Error('Save failed');
        await loadLinks();
        closeModal();
        logActivity(id ? `Updated link (${payload.title})` : `Added link (${payload.title})`);
    } catch(err) { 
        console.error(err); 
        alert('Failed to save link'); 
    }
};

        

        // ------------------- delete -------------------
        async function deleteLink(id){ 
            if(!confirm('Delete this link?')) return; 
            try{ 
                const res = await fetch(`/links/delete/${id}`,{ 
                    method:'DELETE', 
                    headers:{ 'X-CSRF-TOKEN': csrf } 
                }); 
                if(!res.ok) throw new Error('Delete failed'); 
                await loadLinks(); 
                logActivity('Deleted link id ' + id); 
            }catch(err){ 
                console.error(err); 
                alert('Delete failed'); 
            } 
        }

        // ------------------- copy -------------------
        function copyToClipboard(text){ 
            navigator.clipboard.writeText(text); 
            logActivity('Copied link to clipboard'); 
            showToast('URL copied to clipboard!', 'success');
        }

        // ------------------- select -------------------
        function selectLink(id){
            const link = links.find(l => l.id === id); 
            if(!link) return;
            
            activeLink = link;
            
            // Platform icon color
            let platformColor = 'bg-indigo-600';
            let platformIcon = 'fas fa-globe';
            switch(link.type) {
                case 'instagram': platformColor = 'bg-instagram'; platformIcon = 'fab fa-instagram'; break;
                case 'facebook': platformColor = 'bg-facebook'; platformIcon = 'fab fa-facebook'; break;
                case 'whatsapp': platformColor = 'bg-whatsapp'; platformIcon = 'fab fa-whatsapp'; break;
                case 'linkedin': platformColor = 'bg-linkedin'; platformIcon = 'fab fa-linkedin'; break;
                case 'youtube': platformColor = 'bg-youtube'; platformIcon = 'fab fa-youtube'; break;
                default: platformColor = 'bg-indigo-600'; platformIcon = 'fas fa-globe';
            }

            linkDetailsEl.innerHTML = `
                <div class="flex items-center space-x-2 sm:space-x-3 mb-3 sm:mb-4">
                    <div class="platform-icon ${platformColor}">
                        <i class="${platformIcon} text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-sm sm:text-lg truncate">${escapeHtml(link.title)}</h4>
                        <a href="${escapeHtml(link.url)}" target="_blank" 
                           class="text-xs sm:text-sm text-indigo-600 truncate block hover:underline">
                           ${escapeHtml(link.url)}
                        </a>
                        <div class="text-xs text-gray-500 mt-0.5">
                            Category: ${escapeHtml(link.category || 'Not specified')}
                        </div>
                    </div>
                </div>
                ${link.note ? `<p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">${escapeHtml(link.note)}</p>` : ''}
            `;

            clicksCountEl.textContent = link.clicks || 0;
            engagementRateEl.textContent = (link.engagement || 0) + '%';
            analyticsSummaryEl.classList.remove('hidden');

            // QR Code with responsive size
            qrWrap.innerHTML = '';
            const qrSize = window.innerWidth <= 640 ? 120 : 140;
            new QRCode(qrWrap, { 
                text: link.url, 
                width: qrSize, 
                height: qrSize,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            // Open button
            document.getElementById('openLinkBtn').onclick = (e) => { 
                e.preventDefault(); 
                trackAndOpen(link.id); 
            };
            
            // Download QR button
            document.getElementById('downloadQR').onclick = () => downloadQRCode();

            renderActivityLog();
        }

        // ------------------- track clicks & open -------------------
        async function trackAndOpen(id, event = null){ 
            if(event) event.stopPropagation(); 
            const link = links.find(l => l.id === id); 
            if(!link) return; 
            
            try{ 
                // increment locally
                link.clicks = (link.clicks||0) + 1; 
                clicksCountEl.textContent = link.clicks;
                
                // persist
                await fetch(`/links/update/${id}`, { 
                    method: 'PUT', 
                    headers: { 
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN': csrf 
                    }, 
                    body: JSON.stringify({ clicks: link.clicks }) 
                });
                logActivity('Clicked link: ' + link.title);
            }catch(err){ 
                console.error(err); 
            }
            
            // open url
            window.open(link.url, '_blank');
            
            // refresh list rendering
            applyFilters();
        }

        // ------------------- QR download -------------------
        function downloadQRCode(){
            const img = qrWrap.querySelector('img') || qrWrap.querySelector('canvas');
            if(!img) return alert('No QR to download');
            
            let downloadUrl = '';
            let fileName = (activeLink ? activeLink.title.replace(/[^a-z0-9]/gi, '_').toLowerCase() : 'qr') + '.png';
            
            if(img.tagName.toLowerCase() === 'img'){
                downloadUrl = img.src;
            } else {
                // canvas
                downloadUrl = img.toDataURL('image/png');
            }
            
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            
            logActivity('Downloaded QR for ' + (activeLink ? activeLink.title : 'link'));
            showToast('QR code downloaded!', 'success');
        }

        // ------------------- activity log -------------------
        function logActivity(text){
            const ts = new Date().toLocaleString();
            const item = `${ts} — ${text}`;
            let arr = JSON.parse(localStorage.getItem('link_activity') || '[]');
            arr.unshift(item);
            if(arr.length > 200) arr.pop();
            localStorage.setItem('link_activity', JSON.stringify(arr));
            renderActivityLog();
        }

        function renderActivityLog(){
            const arr = JSON.parse(localStorage.getItem('link_activity') || '[]');
            activityLogEl.innerHTML = arr.map(a => `<div class="py-1 border-b border-gray-100 last:border-b-0">${escapeHtml(a)}</div>`).join('');
        }

        // ------------------- toast notification -------------------
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-blue-500'
            }`;
            toast.textContent = message;
            toast.style.animation = 'slideIn 0.3s ease-out';
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Add CSS for toast animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // ------------------- responsive adjustments -------------------
        function handleResize() {
            // Adjust page size based on screen width
            if (window.innerWidth <= 768) {
                pageSize = 6;
            } else if (window.innerWidth <= 1024) {
                pageSize = 8;
            } else {
                pageSize = 10;
            }
            
            // Re-render if page size changed
            applyFilters();
            
            // Update modal position
            const modal = document.getElementById('linkModal');
            if (!modal.classList.contains('hidden')) {
                if (window.innerWidth <= 640) {
                    modal.classList.remove('items-center');
                    modal.classList.add('items-start');
                } else {
                    modal.classList.remove('items-start');
                    modal.classList.add('items-center');
                }
            }
        }

        // ------------------- init -------------------
        function init(){
            updateFilterButtons();
            loadLinks();
            renderActivityLog();
            
            // Add resize listener
            window.addEventListener('resize', handleResize);
            
            // Add touch event listeners for mobile
            if (window.innerWidth <= 768) {
                initMobileTouchEvents();
            }
            
            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('linkModal');
                    if (!modal.classList.contains('hidden')) {
                        closeModal();
                    }
                }
            });
            
            // Handle back button on mobile
            window.addEventListener('popstate', function() {
                const modal = document.getElementById('linkModal');
                if (!modal.classList.contains('hidden')) {
                    closeModal();
                    history.pushState(null, null, window.location.pathname);
                }
            });
        }

        // Initialize mobile touch events
        function initMobileTouchEvents() {
            const touchElements = document.querySelectorAll('.touch-target, .platform-icon, button');
            
            touchElements.forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                    this.style.opacity = '0.9';
                });
                
                element.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                });
                
                element.addEventListener('touchcancel', function() {
                    this.style.transform = 'scale(1)';
                    this.style.opacity = '1';
                });
            });
        }

        // start
        document.addEventListener('DOMContentLoaded', init);

    </script>

</body>
</html>

@endsection
