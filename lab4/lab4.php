<?php
session_start();

require_once __DIR__ . '/includes/produkty.php';

$tytulStrony = 'Katalog produktów PHP';
$idGrupy = 'ISI-2-GR4';
$limitIlosci = 5;

$dozwoloneKategorie = ['wszystkie', 'ksiazki', 'akcesoria'];
$wybranaKategoria = 'wszystkie';
$komunikatFiltr = '';

if (isset($_GET['kategoria'])) {
    if (is_string($_GET['kategoria']) && in_array($_GET['kategoria'], $dozwoloneKategorie, true)) {
        $wybranaKategoria = $_GET['kategoria'];
    } else {
        $wybranaKategoria = 'wszystkie';
        $komunikatFiltr = 'Nieprawidłowy parametr kategorii. Wyświetlono pełną listę produktów.';
    }
}

$bladPost = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suroweId = $_POST['produkt'] ?? null;
    $surowaIlosc = $_POST['ilosc'] ?? null;

    if (!is_string($suroweId) || !is_string($surowaIlosc)) {
        http_response_code(400);
        $bladPost = 'Błąd żądania: brak wymaganych pól lub przesłano nieprawidłowy typ danych.';
    } else {
        $idProduktu = filter_var($suroweId, FILTER_VALIDATE_INT);
        $ilosc = filter_var($surowaIlosc, FILTER_VALIDATE_INT);

        if ($idProduktu === false || $ilosc === false) {
            http_response_code(400);
            $bladPost = 'Błąd żądania: identyfikator produktu i ilość muszą być liczbami całkowitymi.';
        } else {
            $znalezionyProdukt = null;
            foreach ($produkty as $item) {
                if ($item['id'] === $idProduktu) {
                    $znalezionyProdukt = $item;
                    break;
                }
            }

            if ($znalezionyProdukt === null) {
                http_response_code(400);
                $bladPost = 'Błąd żądania: wybrany produkt nie istnieje w katalogu.';
            } elseif ($ilosc < 1 || $ilosc > $limitIlosci) {
                http_response_code(400);
                $bladPost = 'Błąd żądania: ilość musi mieścić się w przedziale od 1 do ' . $limitIlosci . '.';
            } elseif ($ilosc > $znalezionyProdukt['stan']) {
                http_response_code(400);
                $bladPost = 'Błąd żądania: brak wystarczającej liczby sztuk w magazynie (dostępne: ' . $znalezionyProdukt['stan'] . ').';
            } else {
                $_SESSION['ostatni_wybor'] = [
                    'id' => $znalezionyProdukt['id'],
                    'ilosc' => $ilosc,
                ];
                header('Location: lab4.php', true, 303);
                exit;
            }
        }
    }
}

$zapisanyTekst = '';
if (isset($_SESSION['ostatni_wybor']) && is_array($_SESSION['ostatni_wybor'])) {
    $zapisaneId = $_SESSION['ostatni_wybor']['id'] ?? null;
    $zapisanaIlosc = $_SESSION['ostatni_wybor']['ilosc'] ?? null;

    foreach ($produkty as $item) {
        if ($item['id'] === $zapisaneId) {
            $zapisanyTekst = $item['nazwa'] . ' (ilość: ' . $zapisanaIlosc . ' szt.)';
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tytulStrony, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

    <?php include __DIR__ . '/includes/naglowek.php'; ?>

    <main>
        <p><a href="index.html">&larr; Powrót do strony głównej</a></p>

        <section class="session-panel" aria-live="polite">
            <h2>Ostatni zapisany wybór w sesji</h2>
            <?php if ($zapisanyTekst !== ''): ?>
                <p>Wybrany produkt: <strong><?php echo htmlspecialchars($zapisanyTekst, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php else: ?>
                <p>Brak zapisanego wyboru w bieżącej sesji.</p>
            <?php endif; ?>
        </section>

        <section class="filter-panel">
            <h2>Filtruj katalog (GET)</h2>
            <form action="lab4.php" method="get">
                <div class="form-group">
                    <label for="kategoria">Kategoria:</label>
                    <select name="kategoria" id="kategoria">
                        <option value="wszystkie" <?php echo $wybranaKategoria === 'wszystkie' ? 'selected' : ''; ?>>Wszystkie</option>
                        <option value="ksiazki" <?php echo $wybranaKategoria === 'ksiazki' ? 'selected' : ''; ?>>Książki</option>
                        <option value="akcesoria" <?php echo $wybranaKategoria === 'akcesoria' ? 'selected' : ''; ?>>Akcesoria</option>
                    </select>
                </div>
                <button type="submit">Filtruj</button>
            </form>
            <?php if ($komunikatFiltr !== ''): ?>
                <p class="error-msg" role="alert"><?php echo htmlspecialchars($komunikatFiltr, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </section>

        <section class="catalog-panel">
            <h2>Lista produktów</h2>
            <div class="products-grid">
                <?php
                $indeks = 0;
                $liczbaProduktow = count($produkty);
                while ($indeks < $liczbaProduktow):
                    $produkt = $produkty[$indeks];
                    $indeks++;

                    if ($wybranaKategoria !== 'wszystkie' && $produkt['kategoria'] !== $wybranaKategoria) {
                        continue;
                    }

                    switch ($produkt['kategoria']) {
                        case 'ksiazki':
                            $etykietaKategorii = 'Książki';
                            break;
                        case 'akcesoria':
                            $etykietaKategorii = 'Akcesoria';
                            break;
                        default:
                            $etykietaKategorii = 'Inna kategoria';
                            break;
                    }

                    if ($produkt['stan'] === 0) {
                        $statusStanu = 'Niedostępny';
                    } elseif ($produkt['stan'] <= 5) {
                        $statusStanu = 'Niski stan';
                    } else {
                        $statusStanu = 'Dostępny';
                    }
                ?>
                    <article class="product-card">
                        <h3><?php echo htmlspecialchars($produkt['nazwa'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p>Kategoria: <?php echo htmlspecialchars($etykietaKategorii, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Status: <strong><?php echo htmlspecialchars($statusStanu, ENT_QUOTES, 'UTF-8'); ?></strong> (stan: <?php echo (int) $produkt['stan']; ?>)</p>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="order-panel">
            <h2>Zapisz wybór produktu (POST)</h2>
            <?php if ($bladPost !== ''): ?>
                <p class="error-msg" role="alert"><?php echo htmlspecialchars($bladPost, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form action="lab4.php" method="post">
                <div class="form-group">
                    <label for="produkt">Wybierz produkt:</label>
                    <select name="produkt" id="produkt">
                        <?php foreach ($produkty as $item): ?>
                            <option value="<?php echo (int) $item['id']; ?>">
                                <?php echo htmlspecialchars($item['nazwa'], ENT_QUOTES, 'UTF-8'); ?> (stan: <?php echo (int) $item['stan']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ilosc">Ilość sztuk:</label>
                    <select name="ilosc" id="ilosc">
                        <?php for ($i = 1; $i <= $limitIlosci; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit">Zapisz wybór</button>
            </form>
        </section>
    </main>

    <footer>
        <p>Stopka strony</p>
    </footer>

</body>
</html>