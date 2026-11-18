<header>
    <nav>
        <ul>
            <li><a href="index.php">Strona główna</a></li>
            <li><a href="index.php?idp=produkt">Produkt</a></li>
            <li><a href="index.php?idp=kontakt">Kontakt</a></li>
            <li><a href="index.php?idp=filmy">Filmy</a></li>
            <li><a href="lab4.php" aria-current="page">Katalog PHP</a></li>
        </ul>
        <button type="button" id="theme-toggle" class="btn-theme" aria-pressed="false">Tryb ciemny</button>
    </nav>
</header>

<section class="php-banner">
    <h1><?php echo htmlspecialchars($tytulStrony, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>Identyfikator grupy: <strong><?php echo htmlspecialchars($idGrupy, ENT_QUOTES, 'UTF-8'); ?></strong></p>
</section>