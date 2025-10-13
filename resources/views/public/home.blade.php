<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Laravel CMS</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome to Laravel CMS</h1>
            <nav>
                <a href="/">Home</a>
                <a href="/login">Login</a>
            </nav>
        </header>
        
        <main>
            <section>
                <h2>Default Theme</h2>
                <p>This is the default theme homepage. You can customize this theme or create new themes in the resources/views/themes directory.</p>
                
                <div class="cta">
                    <a href="/admin" class="btn btn-primary">Go to Admin Dashboard</a>
                    <a href="/login" class="btn btn-secondary">Login</a>
                </div>
            </section>
        </main>
        
        <footer>
            <p>&copy; {{ date('Y') }} Laravel CMS. All rights reserved.</p>
        </footer>
    </div>
    
    @vite('resources/js/app.js')
</body>
</html>
