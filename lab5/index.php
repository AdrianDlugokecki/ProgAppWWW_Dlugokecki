<?php
$idProjektu = 'sklep-demo';

$podstrony = [
    'glowna' => [
        'tytul' => 'Strona główna',
        'plik' => 'html/glowna.html',
    ],
    'produkt' => [
        'tytul' => 'Produkt',
        'plik' => 'html/produkt.html',
    ],
    'kontakt' => [
        'tytul' => 'Kontakt',
        'plik' => 'html/kontakt.html',
    ],
    'filmy' => [
        'tytul' => 'Filmy',
        'plik' => 'html/filmy.html',
    ],
];

$aktywneIdp = 'glowna';
$kodOdpowiedzi = 200;
$komunikatBledu = '';
$sciezkaWidoku = null;

if (isset($_GET['idp'])) {
    if (!is_string($_GET['idp'])) {
        $kodOdpowiedzi = 404;
        $aktywneIdp = '';
        $komunikatBledu = 'Nie znaleziono podstrony (nieprawidłowy format parametru).';
    } elseif ($_GET['idp'] === '') {
        $aktywneIdp = 'glowna';
    } elseif (array_key_exists($_GET['idp'], $podstrony)) {
        $aktywneIdp = $_GET['idp'];
    } else {
        $kodOdpowiedzi = 404;
        $aktywneIdp = '';
        $komunikatBledu = 'Nie znaleziono żądanej podstrony.';
    }
}

if ($kodOdpowiedzi === 200) {
    $wpis = $podstrony[$aktywneIdp];
    $tytulWidoku = $wpis['tytul'];
    $pelnaSciezka = __DIR__ . '/' . $wpis['plik'];

    if (is_file($pelnaSciezka) && is_readable($pelnaSciezka)) {
        $sciezkaWidoku = $pelnaSciezka;
    } else {
        $kodOdpowiedzi = 500;
        $tytulWidoku = 'Błąd serwera (500)';
        $komunikatBledu = 'Wystąpił wewnętrzny błąd serwera podczas ładowania zawartości podstrony.';
    }
} else {
    $tytulWidoku = 'Nie znaleziono strony (404)';
}

http_response_code($kodOdpowiedzi);
?>
<!DOCTYPE html>
<html lang="pl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - <?php echo htmlspecialchars($tytulWidoku, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js" defer></script>
    <script src="vendor/jquery-4.0.0.min.js" defer></script>
    <script src="js/lab3.js" defer></script>
</head>
<body>

    <header>
        <nav>
            <ul>
                <?php foreach ($podstrony as $klucz => $dane): ?>
                    <?php
                    $href = $klucz === 'glowna' ? 'index.php' : 'index.php?idp=' . urlencode($klucz);
                    $czyAktywny = ($kodOdpowiedzi === 200 && $aktywneIdp === $klucz);
                    ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $czyAktywny ? ' aria-current="page"' : ''; ?>>
                            <?php echo htmlspecialchars($dane['tytul'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li><a href="lab4.php">Katalog PHP</a></li>
            </ul>
            <button type="button" id="theme-toggle" class="btn-theme" aria-pressed="false">Tryb ciemny</button>
        </nav>
    </header>

    <main>
        <?php if ($kodOdpowiedzi === 200 && $sciezkaWidoku !== null): ?>
            <?php include $sciezkaWidoku; ?>
        <?php else: ?>
            <section class="error-panel" role="alert">
                <h1><?php echo htmlspecialchars($tytulWidoku, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($komunikatBledu, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><a href="index.php">Wróć na stronę główną</a></p>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>Stopka strony | Projekt: <strong><?php echo htmlspecialchars($idProjektu, ENT_QUOTES, 'UTF-8'); ?></strong></p>
    </footer>

</body>
</html>