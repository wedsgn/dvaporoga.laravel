<div id="cookie-banner" class="cookie-banner">
    <div class="cookie-banner__container">
        <span class="cookie-banner__text">
            Мы используем файлы cookies для улучшения работы сайта.
            Оставаясь на нашем сайте, вы соглашаетесь с условиями использования файлов cookies.
        </span>

        <button id="cookie-accept" class="cookie-banner__button">
            Принять
        </button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const banner = document.getElementById("cookie-banner");
    const button = document.getElementById("cookie-accept");

    if (!banner || !button) return;

    function getFrames() {
        return {
            buttonFrame: document.getElementById("teletype-widget-component-button"),
            popupFrame: document.getElementById("teletype-widget-component-popup")
        };
    }

    function resetTeletypePosition() {
        const { buttonFrame, popupFrame } = getFrames();

        if (buttonFrame) {
            buttonFrame.style.removeProperty("bottom");
        }

        if (popupFrame) {
            popupFrame.style.removeProperty("bottom");
        }
    }

    function updateUI() {
        const accepted = localStorage.getItem("cookieAccepted") === "true";
        const { buttonFrame, popupFrame } = getFrames();

        if (accepted) {
            banner.style.display = "none";
            document.body.classList.remove("cookie-visible");
            document.documentElement.style.setProperty("--cookie-height", "0px");
            resetTeletypePosition();
            return;
        }

        banner.style.display = "block";

        const height = banner.offsetHeight || 0;
        const gap = window.innerWidth <= 768 ? 12 : 20;
        const buttonBottom = height + gap;

        document.documentElement.style.setProperty("--cookie-height", height + "px");
        document.body.classList.add("cookie-visible");

        if (buttonFrame) {
            buttonFrame.style.setProperty("bottom", buttonBottom + "px", "important");
        }

        if (popupFrame) {
            const popupHeight = popupFrame.offsetHeight || 100;
            popupFrame.style.setProperty(
                "bottom",
                (buttonBottom + popupHeight) + "px",
                "important"
            );
        }
    }

    function waitTeletype() {
        let tries = 0;

        const t = setInterval(() => {
            const { buttonFrame } = getFrames();

            if (buttonFrame) {
                updateUI();
                clearInterval(t);
                return;
            }

            tries++;
            if (tries > 50) clearInterval(t);
        }, 300);
    }

    updateUI();
    waitTeletype();

    button.addEventListener("click", function () {
        localStorage.setItem("cookieAccepted", "true");
        updateUI();
    });

    window.addEventListener("resize", updateUI);
});
</script>
