<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <x-sidebar />

<main class="lg:ml-64 min-h-screen relative bg-white pb-28 lg:pb-0">
    <div class="max-w-7xl mx-auto w-full">
        {{ $slot }}
    </div>
</main>

</body>
</html>