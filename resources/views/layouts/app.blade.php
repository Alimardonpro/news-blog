<!DOCTYPE html>
<html lang="uz" class="{{ request()->cookie('sidebar_collapsed') === 'true' ? 'sidebar-collapsed' : '' }}">
<head>
    <script>
        (function() {
            try {
                var stored = localStorage.getItem('sidebar_collapsed');
                if (stored === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                } else if (stored === 'false') {
                    document.documentElement.classList.remove('sidebar-collapsed');
                }
            } catch(e) {}
        })();
    </script>
    <style>
        html { scrollbar-gutter: stable; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        
        :root {
            --sidebar-width: 16rem;
        }
        html.sidebar-collapsed {
            --sidebar-width: 5rem;
        }

        /* Set sidebar & main widths instantly based on CSS variable before JS runs */
        aside#app-sidebar {
            width: var(--sidebar-width) !important;
        }
        main#app-main {
            margin-left: 0 !important;
        }
        @media (min-width: 1024px) {
            main#app-main {
                margin-left: var(--sidebar-width) !important;
            }
        }

        /* Instant style rules for collapsed state before Alpine executes */
        html.sidebar-collapsed .sidebar-text-hide {
            display: none !important;
        }
        html.sidebar-collapsed .sidebar-mini-show {
            display: flex !important;
        }
        html:not(.sidebar-collapsed) .sidebar-mini-show {
            display: none !important;
        }

        html.sidebar-collapsed .sidebar-header-box {
            justify-content: center !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        html.sidebar-collapsed .sidebar-nav-link {
            justify-content: center !important;
            width: 3rem !important;
            height: 3rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding: 0 !important;
        }
        html.sidebar-collapsed .sidebar-nav-inner {
            justify-content: center !important;
        }
        html.sidebar-collapsed .sidebar-footer-box {
            justify-content: center !important;
        }
        html.sidebar-collapsed .sidebar-toggle-icon {
            transform: rotate(180deg) !important;
        }

        /* Transitions ONLY run when interacting via toggle button, NEVER during page load */
        .sidebar-animated aside#app-sidebar,
        .sidebar-animated main#app-main,
        .sidebar-animated .sidebar-header-box,
        .sidebar-animated .sidebar-nav-link,
        .sidebar-animated .sidebar-nav-inner,
        .sidebar-animated .sidebar-footer-box,
        .sidebar-animated .sidebar-toggle-icon {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Minnagram') }}</title>
    <!-- Ikonkalar uchun FontAwesome qo'shildi -->
     <!-- Space Grotesk ulanishi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ 
    collapsed: (function() {
        try {
            var stored = localStorage.getItem('sidebar_collapsed');
            if (stored !== null) return stored === 'true';
            return document.documentElement.classList.contains('sidebar-collapsed');
        } catch(e) {
            return document.documentElement.classList.contains('sidebar-collapsed');
        }
    })(),
    toggleSidebar() {
        document.documentElement.classList.add('sidebar-animated');
        this.collapsed = !this.collapsed;
        try {
            localStorage.setItem('sidebar_collapsed', this.collapsed);
            document.cookie = 'sidebar_collapsed=' + this.collapsed + '; path=/; max-age=31536000; SameSite=Lax';
        } catch(e) {}
        if (this.collapsed) {
            document.documentElement.classList.add('sidebar-collapsed');
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
        }
    }
}" class="font-sans antialiased bg-[#f8f9fa] overflow-x-hidden"> <!-- Fon biroz ochartirildi, kontrast yaxshilanishi uchun -->
    
    <x-sidebar />

    <main id="app-main" 
          class="min-h-screen flex flex-col relative">
        <div class="flex-1 w-full">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.addEventListener('livewire:navigated', () => { window.scrollTo(0, 0); });
    </script>
</body>
</html>