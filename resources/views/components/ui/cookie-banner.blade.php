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

    function updateUI() {
        const accepted = localStorage.getItem("cookieAccepted") === "true";

        if (accepted) {
            banner.style.display = "none";
            document.body.classList.remove("cookie-visible");
            document.documentElement.style.setProperty("--cookie-height", "0px");
            return;
        }

        banner.style.display = "block";

        const bannerHeight = banner.offsetHeight || 0;

        document.documentElement.style.setProperty("--cookie-height", bannerHeight + "px");
        document.body.classList.add("cookie-visible");
    }

    updateUI();

    button.addEventListener("click", function () {
        localStorage.setItem("cookieAccepted", "true");
        updateUI();
    });

    window.addEventListener("resize", updateUI);
});
</script>
