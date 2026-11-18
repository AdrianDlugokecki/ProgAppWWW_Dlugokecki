1.Motyw i nawigacja

Oczekiwany wynik: zmiana motywu utrzymuje się po odświeżeniu i zmianie podstrony; aria-pressed odpowiada stanowi.

Zaobserwowany wynik: działa poprawnie, stan zapisuje się w localStorage, a aria-pressed aktualizuje się zgodnie z motywem.

2.Start trzy razy potem Stop i Start 

Oczekiwany wynik: działa jeden interwał; Stop zatrzymuje, Start pokazuje aktualny czas.

Zaobserwowany wynik: wielokrotne kliknięcie Start nie tworzy nowych interwałów; Stop zatrzymuje czas, wznowienie pokazuje bieżącą sekundę.

3.Fragment nazwy ze spacjami i różną wielkością liter

Oczekiwany wynik: pasujące karty widoczne; licznik zgadza się z widokiem.

Zaobserwowany wynik: wpisanie "  kLaWiAtUrA  " zostawia 1 kartę i pokazuje Wyświetlane produkty: 1 z 3.

4.Brak dopasowania i wyczyszczenie pola

Oczekiwany wynik: komunikat o braku wyników; wyczyszczenie przywraca 3 karty.

Zaobserwowany wynik: dla "laptop" pojawia się komunikat o braku wyników (0 z 3), po wyczyszczeniu wracają 3 karty.

5.Cena 1999 gr oraz ilość 3

Oczekiwany wynik: wynik 59,97 zł; sprawdzenie funkcji w konsoli.

Zaobserwowany wynik: w konsoli formatujCenePLN(obliczWartoscGrosze(1999, 3)) zwraca "59,97 zł".

6.Ilość pusta, 0, 6 oraz 1,5

Oczekiwany wynik: brak błędnej wyceny i brak podsumowania.

Zaobserwowany wynik: wyświetla się instrukcja podania liczby 1–5; podsumowanie nie jest generowane.

7.Dziesięć spacji oraz tekst
<b>Test</b>

Oczekiwany wynik: spacje odrzucone; znaczniki pokazane jako zwykły tekst.

Zaobserwowany wynik: 10 spacji wyświetla błąd i przenosi fokus na pole; <b>Test</b> wyświetla się dosłownie dzięki textContent.

8.Klawiatura widoki i wyłączony
JavaScript

Oczekiwany wynik: obsługa klawiaturą w obu szerokościach; bez JS treść i menu dostępne.

Zaobserwowany wynik: nawigacja Tab i fokus działają przy 375 px i 1280 px; po wyłączeniu JS katalog i menu są dostępne.
