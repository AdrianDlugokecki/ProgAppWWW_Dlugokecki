$(function () {
    const $sekcja = $("#lab3-demo");
    if ($sekcja.length === 0) {
        return;
    }

    const mediaMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    function aktualizujPreferencjeRuchu() {
        $.fx.off = mediaMotion.matches;
    }
    aktualizujPreferencjeRuchu();
    mediaMotion.addEventListener("change", aktualizujPreferencjeRuchu);

    const BAZA_W = 160;
    const BAZA_H = 90;

    const $animA = $sekcja.find("#anim-a");
    let powiekA = false;

    $animA.on("click", function () {
        powiekA = !powiekA;
        $(this).attr("aria-pressed", String(powiekA));

        const celW = powiekA ? 240 : BAZA_W;
        const celH = powiekA ? 130 : BAZA_H;

        $(this).stop(true, false).animate({
            width: celW,
            height: celH
        }, 300);
    });

    const $animB = $sekcja.find("#anim-b");
    let czyHoverB = false;
    let czyFocusB = false;

    function aktualizujAnimacjeB() {
        const aktywny = czyHoverB || czyFocusB;
        const celW = aktywny ? 220 : BAZA_W;
        const celH = aktywny ? 120 : BAZA_H;

        $animB.stop(true, false).animate({
            width: celW,
            height: celH
        }, 300);
    }

    $animB.on("mouseenter", function () {
        czyHoverB = true;
        aktualizujAnimacjeB();
    });

    $animB.on("mouseleave", function () {
        czyHoverB = false;
        aktualizujAnimacjeB();
    });

    $animB.on("focus", function () {
        czyFocusB = true;
        aktualizujAnimacjeB();
    });

    $animB.on("blur", function () {
        czyFocusB = false;
        aktualizujAnimacjeB();
    });

    const $animC = $sekcja.find("#anim-c");
    const $statusC = $sekcja.find("#anim-c-status");
    const MAX_KROKI = 5;
    let krokiC = 0;

    function odswiezStatusC(szer, wys) {
        $statusC.text("Kroki animacji C: " + krokiC + " / " + MAX_KROKI + " (" + szer + " × " + wys + " px)");
    }

    $animC.on("click", function () {
        if ($(this).is(":animated") || krokiC >= MAX_KROKI) {
            return;
        }

        krokiC += 1;
        const celW = BAZA_W + krokiC * 20;
        const celH = BAZA_H + krokiC * 10;

        odswiezStatusC(celW, celH);

        $(this).animate({
            width: celW,
            height: celH
        }, 300);
    });

    $sekcja.find("#reset-ac").on("click", function () {
        powiekA = false;
        $animA
            .stop(true, false)
            .attr("aria-pressed", "false")
            .css({
                width: BAZA_W + "px",
                height: BAZA_H + "px"
            });

        krokiC = 0;
        $animC
            .stop(true, false)
            .css({
                width: BAZA_W + "px",
                height: BAZA_H + "px"
            });
        odswiezStatusC(BAZA_W, BAZA_H);
    });

    const $btnSerwer = $sekcja.find("#check-server");
    const $statusSerwer = $sekcja.find("#server-status");

    $btnSerwer.on("click", function () {
        $btnSerwer.prop("disabled", true);
        $statusSerwer.text("Status: trwa łączenie z serwerem...");

        $.ajax({
            url: "status.php",
            method: "GET",
            dataType: "text",
            timeout: 5000
        })
            .done(function (odpowiedz) {
                $statusSerwer.text("Odpowiedź serwera: " + odpowiedz);
            })
            .fail(function (jqXHR, textStatus) {
                $statusSerwer.text("Błąd połączenia (" + textStatus + ", kod: " + jqXHR.status + ")");
            })
            .always(function () {
                $btnSerwer.prop("disabled", false);
            });
    });
});