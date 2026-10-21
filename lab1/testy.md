# Raport z testów - Laboratorium 1
Testowano w przeglądarce Google Chrome.
1. Walidacja struktury HTML
Sprawdzono pliki 'index.html', 'produkt.html' i 'kontakt.html' w walidatorze Nu HTML Checker .
Wynik: Brak błędów w kodzie.
Po wyłączeniu stylów CSS w DevTools treść i nagłówki czytają się po kolei, od góry do dołu.
2. Testy RWD 
-  375 px: Produkty układają się w jednej kolumnie, formularz mieści się na ekranie, strona nie rozciąga się w bok.
-  1280 px: Produkty przechodzą w 3 kolumny . Zdjęcia zachowują swoje proporcje.
 3. Nawigacja klawiaturą
Tab i Shift+Tab sprawnie przechodzą po menu, linkach i polach formularza.
Enter otwiera zaznaczone linki.
Aktywne elementy mają widoczną ramkę 
Kliknięcie w napis etykiety label przenosi kursor prosto do pola tekstowego.
 4. Testy formularza 
Pusty formularz: Przeglądarka blokuje wysłanie na polu e-mail.
Zły e-mail: Wpisanie tekstu bez @ blokuje formularz.
Zła liczba sztuk: Wpisanie 0 lub 6 jest blokowane przez minimum 1 i max 5.
Za krótka wiadomość: Wpisanie mniej niż 10 znaków blokuje wysłanie przez minlength="10".
Poprawne dane: Formularz wysyła się prawidłowo, parametry to: 'email', 'produkt', 'ilosc' i 'wiadomosc'.
5. Eksperyment z atrybutem name
Przebieg: Usunięto 'name="email" z pola e-mail w 'kontakt.html'
Obserwacja: Po wysłaniu formularza w adresie URL zabrakło wpisanego adresu e-mail.
Wniosek: 'id' powiązuje etykiety 'label' i stylami, a'name' odpowiada za przesłyłanie wartości pola do serwera w adresie URL. 
Na koniec przywrócono atrybut name="email' w kodzie.
