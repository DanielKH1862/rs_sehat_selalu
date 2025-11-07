<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Antrian - RS Sehat Selalu</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @livewireStyles
</head>
<body>
    <livewire:display-antrian />
    
    @livewireScripts
</body>
</html>
