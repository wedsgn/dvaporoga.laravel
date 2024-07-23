<section class="marks-section section">
    <div class="container">
        <h2 class="h2">Выберите автозапчасти по марке</h2>

        <div class="mark__wrap">
            <x-concern-card title="VOLKSWAGEN" image="images/mark/ww.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="skoda" image="images/mark/skoda.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="volvo" image="images/mark/volvo.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="Opel" image="images/mark/opel.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="kia" image="images/mark/kia.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="Reno" image="images/mark/reno.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="Mercedes" image="images/mark/mercedes.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="Audi" image="images/mark/audi.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="bmw" image="images/mark/bmw.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="bmw" image="images/mark/bmw.png" :link="route('catalog')" :count="50" />
            <x-concern-card title="land rover" image="images/mark/land-rover.png" :link="route('catalog')"
                :count="50" />
            <x-concern-card title="hyundai" image="images/mark/hyundai.png" :link="route('catalog')" :count="50" />
        </div>
        <a href="{{ route('catalog') }}" class="btn mark-section-btn">Все марки</a>
    </div>
</section>
