<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musée d'amusé</title>
    <link rel="stylesheet" href="styles/style.css">
    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<header class="header">
    <a href="#" class="logo">Musée d'amusé</a>

    <i class='bx bx-menu' id="menu-icon"></i>

    <nav class="navbar">
        <a href="login/login.php">Login</a>
    </nav>
</header>



<section class="ticket" id="ticket">
    <div class="container">
        <div class="card">
            <div class="content">
                <h2>01</h2>
                <h3>Temporaire</h3>
                <p>Explorez l'avenir avec notre exposition sur les nouvelles technologies.
                    Plongez dans le monde du progrès technologique.</p>
                <a href="visiteurs/ticket1.php">Voire Plus</a>
            </div>
        </div>

        <div class="card">
            <div class="content">
                <h2>02</h2>
                <h3>Permanante</h3>
                <p>Explorez notre exposition temporaire exclusive, une collection unique d'œuvres et d'artéfacts
                    rares à découvrir absolument.</p>
                <a href="visiteurs/ticket2.php">Voire Plus</a>
            </div>
        </div>

        <div class="card">
            <div class="content">
                <h2>03</h2>
                <h3>2 en 1</h3>
                <p>Accès complet à nos expositions permanente et temporaire. Explorez une diversité d'objets fascinants
                    et d'œuvres d'art.</p>
                <a href="visiteurs/ticket3.php">Voire Plus</a>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript" src="vanilla-tilt.js"></script>
<script>
    VanillaTilt.init(document.querySelectorAll(".card"), {
        max: 25,
        speed: 400,
        glare: true,
        "max-glare": 1
    });
</script>
</body>
</html>