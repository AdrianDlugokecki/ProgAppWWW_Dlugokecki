const PRODUKTY = [
    { id: "produkt1", nazwa: "Klawiatura Mechaniczna", cenaGrosze: 29900 },
    { id: "produkt2", nazwa: "Mysz Bezprzewodowa", cenaGrosze: 15900 },
    { id: "produkt3", nazwa: "Słuchawki nauszne", cenaGrosze: 24900 }
];

function obliczWartoscGrosze(cenaGrosze, ilosc) {
    return cenaGrosze * ilosc;
}

function formatujCenePLN(kwotaGrosze) {
    const formatter = new Intl.NumberFormat("pl-PL", {
        style: "currency",
        currency: "PLN"
    });
    return formatter.format(kwotaGrosze / 100);
}

function initMotyw() {
    const themeToggleBtn = document.getElementById("theme-toggle");
    const htmlElement = document.documentElement;
    const STORAGE_KEY = "sklep-motyw";

    function zastosujMotyw(motyw) {
        const poprawnyMotyw = motyw === "dark" ? "dark" : "light";
        htmlElement.setAttribute("data-theme", poprawnyMotyw);

        if (themeToggleBtn) {
            const czyCiemny = poprawnyMotyw === "dark";
            themeToggleBtn.setAttribute("aria-pressed", String(czyCiemny));
            themeToggleBtn.textContent = czyCiemny ? "Tryb jasny" : "Tryb ciemny";
        }
    }

    const zapisanyMotyw = localStorage.getItem(STORAGE_KEY);
    zastosujMotyw(zapisanyMotyw);

    if (!themeToggleBtn) return;

    themeToggleBtn.addEventListener("click", () => {
        const aktualnyMotyw = htmlElement.getAttribute("data-theme");
        const nowyMotyw = aktualnyMotyw === "dark" ? "light" : "dark";
        localStorage.setItem(STORAGE_KEY, nowyMotyw);
        zastosujMotyw(nowyMotyw);
    });
}

function initZegar() {
    const clockDisplay = document.getElementById("clock-display");
    const startBtn = document.getElementById("clock-start");
    const stopBtn = document.getElementById("clock-stop");

    if (!clockDisplay || !startBtn || !stopBtn) return;

    let intervalId = null;

    const formatterDaty = new Intl.DateTimeFormat("pl-PL", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false
    });

    function aktualizujZegar() {
        const teraz = new Date();
        clockDisplay.textContent = formatterDaty.format(teraz);
    }

    function uruchomZegar() {
        if (intervalId !== null) {
            return;
        }
        aktualizujZegar();
        intervalId = setInterval(aktualizujZegar, 1000);
    }

    function zatrzymajZegar() {
        if (intervalId !== null) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    startBtn.addEventListener("click", uruchomZegar);
    stopBtn.addEventListener("click", zatrzymajZegar);

    uruchomZegar();
}

function initWyszukiwarka() {
    const searchInput = document.getElementById("search-input");
    const searchCount = document.getElementById("search-count");
    const noResultsMsg = document.getElementById("no-results");
    const productCards = document.querySelectorAll(".product-card");

    if (!searchInput || !searchCount || !noResultsMsg || productCards.length === 0) return;

    const calkowitaLiczba = productCards.length;

    searchInput.addEventListener("input", () => {
        const zapytanie = searchInput.value.trim().toLowerCase();
        let widoczne = 0;

        productCards.forEach((card) => {
            const tytulEl = card.querySelector("h2");
            const nazwaProduktu = tytulEl ? tytulEl.textContent.toLowerCase() : "";

            if (zapytanie === "" || nazwaProduktu.includes(zapytanie)) {
                card.hidden = false;
                widoczne++;
            } else {
                card.hidden = true;
            }
        });

        searchCount.textContent = `Wyświetlane produkty: ${widoczne} z ${calkowitaLiczba}`;
        noResultsMsg.hidden = widoczne > 0;
    });
}

function initFormularz() {
    const form = document.getElementById("contact-form");
    if (!form) return;

    const produktSelect = document.getElementById("produkt");
    const iloscInput = document.getElementById("ilosc");
    const wiadomoscInput = document.getElementById("wiadomosc");
    const wiadomoscError = document.getElementById("wiadomosc-error");
    const pricePreview = document.getElementById("price-preview");

    const summaryBox = document.getElementById("order-summary");
    const summaryProduct = document.getElementById("summary-product");
    const summaryQuantity = document.getElementById("summary-quantity");
    const summaryTotal = document.getElementById("summary-total");
    const summaryMessage = document.getElementById("summary-message");

    function pobierzWybranyProdukt() {
        const wybraneId = produktSelect.value;
        return PRODUKTY.find((p) => p.id === wybraneId) || null;
    }

    function parsujPoprawnaIlosc(wartoscTekstowa) {
        const oczyszczona = wartoscTekstowa.trim();
        if (oczyszczona === "") return null;

        const liczba = Number(oczyszczona);
        if (!Number.isInteger(liczba) || liczba < 1 || liczba > 5) {
            return null;
        }
        return liczba;
    }

    function ukryjPodsumowanie() {
        summaryBox.hidden = true;
    }

    function aktualizujWycene() {
        ukryjPodsumowanie();
        const produkt = pobierzWybranyProdukt();
        const ilosc = parsujPoprawnaIlosc(iloscInput.value);

        if (!produkt || ilosc === null) {
            pricePreview.textContent = "Wybierz produkt i podaj poprawną całkowitą liczbę sztuk (od 1 do 5).";
            return;
        }

        const sumaGrosze = obliczWartoscGrosze(produkt.cenaGrosze, ilosc);
        pricePreview.textContent = formatujCenePLN(sumaGrosze);
    }

    produktSelect.addEventListener("change", aktualizujWycene);
    iloscInput.addEventListener("input", aktualizujWycene);

    wiadomoscInput.addEventListener("input", () => {
        ukryjPodsumowanie();
        if (wiadomoscInput.value.trim().length >= 10) {
            wiadomoscError.hidden = true;
            wiadomoscError.textContent = "";
        }
    });

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const produkt = pobierzWybranyProdukt();
        const ilosc = parsujPoprawnaIlosc(iloscInput.value);
        const trescWiadomosci = wiadomoscInput.value.trim();

        if (!produkt || ilosc === null) {
            ukryjPodsumowanie();
            return;
        }

        if (trescWiadomosci.length < 10) {
            wiadomoscError.textContent = "Wiadomość po usunięciu skrajnych spacji musi mieć co najmniej 10 znaków.";
            wiadomoscError.hidden = false;
            ukryjPodsumowanie();
            wiadomoscInput.focus();
            return;
        }

        wiadomoscError.hidden = true;
        wiadomoscError.textContent = "";

        const sumaGrosze = obliczWartoscGrosze(produkt.cenaGrosze, ilosc);

        summaryProduct.textContent = produkt.nazwa;
        summaryQuantity.textContent = String(ilosc);
        summaryTotal.textContent = formatujCenePLN(sumaGrosze);
        summaryMessage.textContent = trescWiadomosci;

        summaryBox.hidden = false;
    });
}

initMotyw();
initZegar();
initWyszukiwarka();
initFormularz();