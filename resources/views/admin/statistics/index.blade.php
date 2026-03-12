@extends('layouts.app')

@section('title', 'Kasutusjuhend')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Kasutusjuhend</h1>

    <div class="card">
        <div class="card-header">
            School Bistro kasutusjuhend
        </div>

        <div class="card-body">
            <p class="text-muted mb-4">
                Käesolev juhend annab ülevaate School Bistro haldusliidese peamistest vaadetest ja nende kasutamisest.
                Siit leiad juhised menüüde, toitude, kategooriate, allergeenide, taustapiltide, kasutajate ja statistika haldamiseks.
            </p>

            <div class="accordion" id="manualAccordion">

                {{-- 1. Sissejuhatus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseOne"
                            aria-expanded="true"
                            aria-controls="collapseOne">
                            1. Sissejuhatus
                        </button>
                    </h2>

                    <div id="collapseOne" class="accordion-collapse collapse show"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">
                            <p>
                                School Bistro on veebipõhine süsteem, mis võimaldab hallata päevamenüüsid,
                                menüüdes kuvatavaid toite, toidukategooriaid, allergeene, taustapilte ning statistikat.
                            </p>
                            <p class="mb-0">
                                Haldusliides on mõeldud kasutamiseks administraatorile või töötajale, kes vastutab menüüde
                                ettevalmistamise ja ajakohastamise eest. Enamik toiminguid toimub eraldi halduslehtedel,
                                kus saab lisada, muuta, kustutada ja järjestada andmeid.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 2. Sisselogimine --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo"
                            aria-expanded="false"
                            aria-controls="collapseTwo">
                            2. Sisselogimine
                        </button>
                    </h2>

                    <div id="collapseTwo"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">
                            <ol>
                                <li>Ava veebibrauser ja sisesta süsteemi aadress.</li>
                                <li>Sisesta oma kasutajanimi ja parool.</li>
                                <li>Vajuta nuppu <strong>„Logi sisse“</strong>.</li>
                                <li>Pärast edukat sisselogimist suunatakse sind <strong>Menüü halduse</strong> lehele.</li>
                            </ol>

                            <p class="mb-0">
                                Kui sisselogimine ei õnnestu, kontrolli, kas sisestasid õiged andmed ning kas klaviatuuri
                                keelevalik või suurtähtede lukk ei põhjusta viga.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 3. Menüüde haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseThree"
                            aria-expanded="false"
                            aria-controls="collapseThree">
                            3. Menüüde haldus
                        </button>
                    </h2>

                    <div id="collapseThree"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Menüü halduse lehel kuvatakse kõik olemasolevad menüüd kuupäevade kaupa.
                                Sellelt lehelt saab menüüsid lisada, vaadata, muuta, aktiveerida, kopeerida ja kustutada.
                            </p>

                            <h6 class="fw-semibold mt-3">Peamised tegevused</h6>
                            <ul>
                                <li>
                                    <strong>Määra aktiivseks</strong> – muudab menüü aktiivseks ning see kuvatakse kliendile.
                                    Aktiivseks saab määrata ainult tänase kuupäeva menüü ning korraga saab aktiivne olla ainult üks menüü.
                                </li>
                                <li>
                                    <strong>Lisa toit</strong> – avab vormi ühe toidu lisamiseks valitud menüüsse.
                                </li>
                                <li>
                                    <strong>Lisa toidud</strong> – võimaldab lisada või muuta mitu toitu korraga.
                                </li>
                                <li>
                                    <strong>Vaata</strong> – avab menüü detailvaate, kus saab näha menüü sisu ning muuta toite,
                                    hindu ja saadavust.
                                </li>
                                <li>
                                    <strong>Muuda</strong> – võimaldab muuta menüü kuupäeva, menüü tüüpi ja menüü päises kuvatavaid tekstiridu.
                                </li>
                                <li>
                                    <strong>Kopeeri</strong> – loob olemasoleva menüü põhjal uue menüü, mida saab kasutada sarnase menüü loomisel.
                                </li>
                                <li>
                                    <strong>Kustuta</strong> – eemaldab menüü süsteemist.
                                </li>
                            </ul>

                            <h6 class="fw-semibold mt-3">Uue menüü lisamine</h6>
                            <ol class="mb-0">
                                <li>Vajuta nuppu <strong>„Lisa menüü“</strong>.</li>
                                <li>Vali menüü kuupäev.</li>
                                <li>Vali sobiv menüü tüüp.</li>
                                <li>Soovi korral lisa päisesse kuni kolm tekstirida.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- 4. Toitude lisamine --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFour"
                            aria-expanded="false"
                            aria-controls="collapseFour">
                            4. Toitude lisamine
                        </button>
                    </h2>

                    <div id="collapseFour"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Toite saab menüüsse lisada kas ükshaaval või mitme kaupa. Toitude lisamisel saab määrata
                                kategooria, nimetuse, hinnad, allergeenid, kuvamise järjekorra ja saadavuse.
                            </p>

                            <h6 class="fw-semibold mt-3">Ühe toidu lisamine</h6>
                            <ol>
                                <li>Vajuta nuppu <strong>„Lisa toit“</strong>.</li>
                                <li>Vali toidule sobiv kategooria.</li>
                                <li>Sisesta toidu nimetus.</li>
                                <li>Sisesta täishind ja vajadusel poolhind.</li>
                                <li>Määra kuva järjekord, kui soovid kindlat kuvamisjärjestust.</li>
                                <li>Vali allergeenid, kui need on toidule määratavad.</li>
                                <li>Määra, kas toit on hetkel saadaval.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold mt-3">Mitme toidu lisamine</h6>
                            <ol class="mb-0">
                                <li>Vajuta nuppu <strong>„Lisa toidud“</strong>.</li>
                                <li>Lisa või muuda mitu toitu samal lehel.</li>
                                <li>Kontrolli andmed üle.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>, et muudatused kinnitada.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- 5. Kategooriate haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFive"
                            aria-expanded="false"
                            aria-controls="collapseFive">
                            5. Kategooriate haldus
                        </button>
                    </h2>

                    <div id="collapseFive"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Kategooriate halduse lehel saab luua menüüdes kasutatavaid kategooriaid ning määrata,
                                millise menüü tüübi alla need kuuluvad. Lisaks saab muuta kategooria nähtavust ja järjestust.
                            </p>

                            <h6 class="fw-semibold mt-3">Uue kategooria lisamine</h6>
                            <ol>
                                <li>Sisesta kategooria nimi.</li>
                                <li>Vali menüü tüüp, mille alla kategooria kuulub.</li>
                                <li>Määra kategooria kuvamise järjekord.</li>
                                <li>Märgi, kas kategooria on nähtav.</li>
                                <li>Vajuta <strong>„Lisa kategooria“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold mt-3">Muud võimalused</h6>
                            <ul class="mb-0">
                                <li><strong>Nähtavus</strong> – määrab, kas kategooriat kuvatakse menüüs kasutajale.</li>
                                <li><strong>Järjestamine</strong> – nooltega <strong>↑</strong> ja <strong>↓</strong> saab muuta kuvamise järjekorda.</li>
                                <li><strong>Muuda</strong> – võimaldab muuta kategooria nime, menüü tüüpi, järjekorda ja nähtavust.</li>
                                <li><strong>Kustuta</strong> – eemaldab kategooria süsteemist.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- 6. Allergeenide haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseSix"
                            aria-expanded="false"
                            aria-controls="collapseSix">
                            6. Allergeenide haldus
                        </button>
                    </h2>

                    <div id="collapseSix"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Allergeenide halduse lehel hallatakse allergeene, mida saab toitude juurde märkida.
                                See aitab kasutajal näha, milliseid allergeene toit võib sisaldada.
                            </p>

                            <h6 class="fw-semibold mt-3">Uue allergeeni lisamine</h6>
                            <ol>
                                <li>Sisesta allergeeni nimi.</li>
                                <li>Sisesta allergeeni kood või lühend.</li>
                                <li>Määra kuvamise järjekord.</li>
                                <li>Vajuta <strong>„Lisa allergeen“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold mt-3">Muud võimalused</h6>
                            <ul class="mb-0">
                                <li><strong>Muuda</strong> – võimaldab muuta allergeeni nime, lühendit ja järjestust.</li>
                                <li><strong>Järjestamine</strong> – nooled <strong>↑</strong> ja <strong>↓</strong> muudavad allergeenide kuvamise järjekorda.</li>
                                <li><strong>Kustuta</strong> – eemaldab allergeeni süsteemist.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- 7. Taustapiltide haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseSeven"
                            aria-expanded="false"
                            aria-controls="collapseSeven">
                            7. Taustapiltide haldus
                        </button>
                    </h2>

                    <div id="collapseSeven"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Taustapiltide halduse lehel saab üles laadida uusi taustapilte, määrata aktiivse pildi
                                ning kustutada mittevajalikke pilte.
                            </p>

                            <h6 class="fw-semibold mt-3">Uue taustapildi lisamine</h6>
                            <ol>
                                <li>Vali arvutist pildifail (JPG või PNG).</li>
                                <li>Soovi korral märgi valik <strong>„Tee kohe aktiivseks“</strong>.</li>
                                <li>Vajuta <strong>„Lae üles“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold mt-3">Muud võimalused</h6>
                            <ul class="mb-0">
                                <li><strong>Aktiivne / Mitteaktiivne</strong> – staatuse märgil vajutades saab muuta aktiivset taustapilti.</li>
                                <li><strong>Lisatud</strong> – kuvatakse pildi lisamise kuupäev ja kellaaeg.</li>
                                <li><strong>Kustuta</strong> – eemaldab pildi süsteemist.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- 8. Kasutajate haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseEight"
                            aria-expanded="false"
                            aria-controls="collapseEight">
                            8. Kasutajate haldus
                        </button>
                    </h2>

                    <div id="collapseEight"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Kasutajate halduse lehel saab lisada uusi kasutajaid, vaadata olemasolevaid kasutajaid
                                ning muuta või kustutada nende andmeid. Selle vaate kaudu hallatakse süsteemi ligipääsu
                                ja kasutajarolle.
                            </p>

                            <h6 class="fw-semibold mt-3">Uue kasutaja lisamine</h6>
                            <ol>
                                <li>Sisesta kasutaja nimi.</li>
                                <li>Sisesta kasutaja e-posti aadress.</li>
                                <li>Sisesta parool ja parooli kinnitus.</li>
                                <li>Vajadusel määra kasutaja <strong>Admin</strong> õigustega kasutajaks.</li>
                                <li>Määra, kas kasutaja on <strong>Aktiivne</strong>.</li>
                                <li>Vajuta <strong>„Lisa kasutaja“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold mt-3">Olemasolevad kasutajad</h6>
                            <p>
                                Kasutajate tabelis kuvatakse kasutaja nimi, e-post, roll, staatus ja võimalikud tegevused.
                                Roll võib olla <strong>Admin</strong> või <strong>Kasutaja</strong>. Staatus näitab, kas
                                kasutaja konto on aktiivne või mitteaktiivne.
                            </p>

                            <h6 class="fw-semibold mt-3">Kasutaja muutmine</h6>
                            <ol>
                                <li>Vajuta kasutaja real nuppu <strong>„Muuda“</strong>.</li>
                                <li>Muuda nime, e-posti, rolli või aktiivsuse staatust.</li>
                                <li>Soovi korral sisesta uus parool ja selle kinnitus.</li>
                                <li>Vajuta <strong>„Salvesta muudatused“</strong>.</li>
                            </ol>

                            <p>
                                Kui parooli ei soovita muuta, võib parooli väljad jätta tühjaks.
                            </p>

                            <h6 class="fw-semibold mt-3">Kasutaja kustutamine</h6>
                            <p class="mb-0">
                                Kasutaja eemaldamiseks vajuta nuppu <strong>„Kustuta“</strong> ja kinnita tegevus.
                                Sisseloginud kasutaja ei saa iseennast kustutada – sellisel juhul on kustutamise nupp mitteaktiivne.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 9. Statistika --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseNine"
                            aria-expanded="false"
                            aria-controls="collapseNine">
                            9. Statistika
                        </button>
                    </h2>

                    <div id="collapseNine"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">

                            <p>
                                Statistika leht annab ülevaate menüüdest, toitudest ja menüütüüpidest.
                                Sellelt lehelt saab näha, milliseid toite on süsteemis enim kasutatud ning otsida
                                konkreetse toidu varasemat esinemist menüüdes.
                            </p>

                            <h6 class="fw-semibold mt-3">Kokkuvõtte kaardid</h6>
                            <ul>
                                <li><strong>Menüüsid kokku</strong> – näitab menüüde koguarvu ja viimase menüü kuupäeva.</li>
                                <li><strong>Erinevaid toite</strong> – näitab unikaalsete toitude arvu ja populaarseimat toitu.</li>
                                <li><strong>Menüütüüpe kokku</strong> – näitab menüütüüpide arvu ja enim kasutatud menüütüüpi.</li>
                            </ul>

                            <h6 class="fw-semibold mt-3">Populaarseimad toidud</h6>
                            <p>
                                Tabel kuvab kümme kõige sagedamini menüüdes esinenud toitu.
                                Tulemusi saab filtreerida menüütüübi ja kategooria järgi.
                            </p>

                            <h6 class="fw-semibold mt-3">Toidu esinemise ajalugu</h6>
                            <ol class="mb-0">
                                <li>Sisesta otsinguväljale soovitud toidu nimi.</li>
                                <li>Vajuta <strong>„Otsi“</strong>.</li>
                                <li>Süsteem kuvab tulemused koos kuupäeva, menüütüübi, kategooria, hinna ja allergeenidega.</li>
                                <li>Võimalusel saab avada ka seotud menüü detailvaate.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- 10. Kokkuvõte --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseTen"
                            aria-expanded="false"
                            aria-controls="collapseTen">
                            10. Kokkuvõte
                        </button>
                    </h2>

                    <div id="collapseTen"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">
                        <div class="accordion-body">
                            <p>
                                School Bistro haldusliides võimaldab hallata kogu menüüga seotud teavet ühest kohast.
                                Süsteem aitab hoida menüüd korrastatuna, ajakohasena ja kasutajale arusaadavana.
                            </p>
                            <p class="mb-0">
                                Igapäevases kasutuses on soovitatav pöörata tähelepanu menüü kuupäevadele,
                                toitude saadavusele, kategooriate järjestusele, kasutajate õigustele ning allergeenide korrektsusele.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection