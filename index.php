<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="pages/images/LogoFv.png" type="image/x-icon">
    <meta neme="theme-color" content="#f1c81e">
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <img src="pages/images/Logo1.png" alt="Logo" class="logo">
        <h1>Bienvenue </h1>
        <ul class="nav-links">
            <li><a href="pages/index.php">Page principale</a></li>
            <li><a href="admin/login.php">Admin Login</a></li>
        </ul>
    </div>
</body>
<style>

    body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(120deg, #e3f2fd 0%, #b3c6e7 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}


.container {
    background: #f5fafd;
    padding: 2.5rem 2rem;
    border-radius: 1.2rem;
    box-shadow: 0 8px 32px rgba(60, 88, 120, 0.10), 0 1.5px 4px rgba(60, 88, 120, 0.08);
    text-align: center;
    min-width: 320px;
    max-width: 400px;
    width: 100%;
}

.logo {
    display: block;
    margin: 0 auto 1.5rem auto;
    max-width: 120px;
    height: auto;
    border-radius: 0.7rem;
    box-shadow: 0 2px 8px rgba(60, 88, 120, 0.10);
    background: #e3f2fd;
    padding: 0.5rem;
}

h1 {
    margin-bottom: 2rem;
    font-size: 2rem;
    color: #f1c81e;
    letter-spacing: 1px;
}

.nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-links li {
    margin: 1rem 0;
}

.nav-links a {
    display: inline-block;
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    color: #fff;
    background: linear-gradient(90deg, #f1c81e 0%, #f1c81e 100%);
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(79, 140, 255, 0.08);
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
}

.nav-links a:hover, .nav-links a:focus {
    background: linear-gradient(90deg, #1976d2 0%, #00bcd4 100%);
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 4px 16px rgba(35, 85, 216, 0.15);
    outline: none;
}

</style>
</html>
