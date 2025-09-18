<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Tangerine:wght@400;700&display=swap" rel="stylesheet">
    @stack('styles')

</head>


<body>
    <header>
        <h1 class="brand">mogitate</h1>
    </header>
    <main class="p-4">
        @yield('content')
    </main>

    <footer class="p-4 text-center text-gray-500">
        &copy; {{ date('Y') }} MyShop
    </footer>
</body>

</html>