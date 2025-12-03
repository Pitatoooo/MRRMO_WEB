<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | @yield('title', 'MDRRMO')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- You can link admin CSS here later --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @yield('head') {{-- Optional: Custom head section --}}
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Optional reusable nav here --}}
    @includeIf('admin.partials.navbar')

    <main class="p-4">
        @yield('content')
    </main>

</body>
</html>
