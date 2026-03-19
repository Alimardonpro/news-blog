<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bloggram</title>
    <!-- Ikonkalar uchun FontAwesome qo'shildi -->
     <!-- Space Grotesk ulanishi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scrollbar-gutter: stable; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8f9fa] overflow-x-hidden"> <!-- Fon biroz ochartirildi, kontrast yaxshilanishi uchun -->
    
    <x-sidebar />

    <main class="lg:ml-64 min-h-screen flex flex-col relative">
        <div class="flex-1 w-full">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.addEventListener('livewire:navigated', () => { window.scrollTo(0, 0); });
    </script>
</body>
</html>