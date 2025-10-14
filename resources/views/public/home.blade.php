<!DOCTYPE html>
<html lang="@locale" dir="@localeDir">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@t('messages.welcome') - {{ config('app.name') }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container">
        <header>
            <h1>@t('messages.welcome_to', ['name' => config('app.name')])</h1>
            <nav>
                <a href="/">@t('messages.home')</a>
                <a href="/login">@t('auth.login')</a>
            </nav>
        </header>
        
        <main>
            <section>
                <h2>@t('messages.default_theme')</h2>
                <p>@t('messages.theme_description')</p>
                
                <div class="cta">
                    <a href="/admin" class="btn btn-primary">@t('admin.nav.dashboard')</a>
                    <a href="/login" class="btn btn-secondary">@t('auth.login')</a>
                </div>
            </section>
        </main>
        
        <footer>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. @t('messages.all_rights_reserved')</p>
        </footer>
    </div>
    
    @vite('resources/js/app.js')
</body>
</html>
