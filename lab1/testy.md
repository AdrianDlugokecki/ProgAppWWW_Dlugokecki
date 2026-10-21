# Raport z testów - Laboratorium 1

Przeglądarka testowa: Google Chrome (najnowsza wersja)

## 1. Walidacja struktury HTML
- **Pliki:** `index.html`, `produkt.html`, `kontakt.html` zostały sprawdzone w narzędziu Nu HTML Checker (by file upload).
- **Oczekiwany wynik:** Brak błędów (Errors).
- **Zaobserwowany wynik:** Kod jest w pełni poprawny, narzędzie nie zgłasza błędów struktury.
- **Wyłączenie CSS:** Po wyłączeniu stylów w DevTools strona zachowuje czytelną, logiczną hierarchię treści (nagłówki `h1`, `h2`, listy i struktura formularza czytają się od góry do dołu).

## 2. Testy widoków responsywnych (RWD)
- **Ekran mobilny (375 px):** Układ produktów wyświetla się w 1 kolumnie. Formularz mieści się całkowicie na ekranie. Brak poziomego paska przewijania.
- **Ekran desktopowy (1280 px):** Układ produktów przełącza się automatycznie na 3 kolumny dzięki regule `@media (min-width: 768px)`. Obrazy zachowują swoje proporcje.

## 3. Testy nawigacji klawiaturą
- **Klawisze Tab / Shift+Tab:** Nawigacja po menu, linkach produktów i polach formularza odbywa się w prawidłowej, intuicyjnej kolejności.
- **Aktywacja:** Naciśnięcie Enter na zaznaczonym linku przenosi do odpowiedniej podstrony.
- **Widoczność fokusu:** Elementy aktywne posiadają widoczny obrys (`:focus-visible`).
- **Etykiety label:** Kliknięcie myszką w tekst etykiety przenieś kursor/fokus bezpośrednio do powiązanego pola wejściowego (`id` / `for`).

## 4. Testy walidacji formularza (`kontakt.html`)
- **Pusty formularz:** Kliknięcie „Sprawdź zapytanie” zatrzymuje wysyłanie na polu e-mail z komunikatem o wymaganym polu.
- **Błędny format e-mail:** Wpisanie wartości bez znaku `@` (np. `test.pl`) powoduje zablokowanie formularza z komunikatem o braku adresu e-mail.
- **Zła liczba sztuk:** Wpisanie wartości `0` lub `6` zostaje zablokowane przez ograniczenie `min="1"` i `max="5"`.
- **Zbyt krótka wiadomość:** Wpisanie mniej niż 10 znaków powoduje zablokowanie formularza przez atrybut `minlength="10"`.
- **Poprawne wysłanie:** Po wpisaniu poprawnych danych strona przeładowuje się, a w adresie URL pojawiają się cztery przesyłane parametry: `email`, `produkt`, `ilosc`, `wiadomosc`.

## 5. Eksperyment z atrybutem name
- **Hipoteza:** Usunięcie atrybutu `name` z pola e-mail spowoduje brak tego klucza i wartości w adresie URL po wysłaniu formularza, mimo że pole ma nadany atrybut `id`.
- **Przebieg i zaobserwowany wynik:** Po usunięciu `name="email"` z kodu i poprawnym wypełnieniu formularza, w adresie URL pojawiły się tylko parametry `produkt`, `ilosc` i `wiadomosc`. Wartość pola e-mail została pominięta.
- **Wyjaśnienie:** Atrybut `id` służy do identyfikacji elementu w pliku HTML (np. powiązanie z `<label for="...">`), natomiast atrybut `name` tworzy nazwę zmiennej/klucza wysyłanego w żądaniu HTTP GET na serwer. Bez `name` przeglądarka nie przesyła wartości danego pola.
- **Przywrócenie:** Atrybut `name="email"` został przywrócony do kodu.