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
                Lühike ja praktiline ülevaade süsteemi peamistest haldusvaadetest ning nende kasutamisest.
            </p>

            <div class="accordion" id="manualAccordion">

                {{-- 1. Sissejuhatus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseOne">
                            1. Sissejuhatus
                        </button>
                    </h2>

                    <div id="collapseOne" class="accordion-collapse collapse show"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">
                            School Bistro on veebipõhine süsteem päevamenüüde, toitude,
                            kategooriate, allergeenide, taustapiltide ja statistika haldamiseks.
                            Käesolev juhend annab lühikese ülevaate süsteemi põhifunktsioonidest.
                        </div>
                    </div>
                </div>


                {{-- 2. Sisselogimine --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo">

                            2. Sisselogimine
                        </button>
                    </h2>

                    <div id="collapseTwo"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">
                            <ol class="mb-0">
                                <li>Ava veebibrauser ja sisesta süsteemi aadress.</li>
                                <li>Sisesta kasutajanimi ja parool.</li>
                                <li>Vajuta <strong>„Logi sisse“</strong>.</li>
                                <li>Pärast edukat sisselogimist avaneb <strong>Menüü halduse</strong> leht.</li>
                            </ol>
                        </div>
                    </div>
                </div>


                {{-- 3. Menüüde haldus --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseThree">

                            3. Menüüde haldus
                        </button>
                    </h2>

                    <div id="collapseThree"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <p>Menüü halduse lehel kuvatakse olemasolevad menüüd kuupäevade kaupa.</p>

                            <ul>
                                <li><strong>Määra aktiivseks</strong> – korraga saab olla aktiivne üks menüü.</li>
                                <li><strong>Lisa toit / Lisa toidud</strong> – võimaldab lisada ühe või mitu toitu.</li>
                                <li><strong>Vaata</strong> – avab menüü detailvaate.</li>
                                <li><strong>Muuda</strong> – võimaldab muuta kuupäeva ja menüü päist.</li>
                                <li><strong>Kustuta</strong> – eemaldab menüü süsteemist.</li>
                                <li><strong>Kopeeri</strong> – loob uue menüü olemasoleva põhjal.</li>
                            </ul>

                            <h6 class="fw-semibold mt-3">Uue menüü lisamine</h6>

                            <ol class="mb-0">
                                <li>Vajuta <strong>„Lisa menüü“</strong>.</li>
                                <li>Vali kuupäev ja menüü tüüp.</li>
                                <li>Lisa vajadusel päise tekstid.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>.</li>
                            </ol>

                        </div>
                    </div>
                </div>


                {{-- 4. Toidud --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFour">

                            4. Toitude lisamine
                        </button>
                    </h2>

                    <div id="collapseFour"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <h6 class="fw-semibold">Ühe toidu lisamine</h6>

                            <ol>
                                <li>Vajuta <strong>„Lisa toit“</strong>.</li>
                                <li>Vali kategooria.</li>
                                <li>Sisesta toidu nimi ja hind.</li>
                                <li>Määra allergeenid ja järjekord.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>.</li>
                            </ol>

                            <h6 class="fw-semibold">Mitme toidu lisamine</h6>

                            <ol class="mb-0">
                                <li>Vajuta <strong>„Lisa toidud“</strong>.</li>
                                <li>Lisa või muuda mitu toitu korraga.</li>
                                <li>Vajuta <strong>„Salvesta“</strong>.</li>
                            </ol>

                        </div>
                    </div>
                </div>


                {{-- 5. Kategooriad --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFive">

                            5. Kategooriate haldus
                        </button>
                    </h2>

                    <div id="collapseFive"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <p>Kategooriate halduses saab lisada ja muuta menüü kategooriaid.</p>

                            <ol>
                                <li>Sisesta kategooria nimi.</li>
                                <li>Vali menüü tüüp.</li>
                                <li>Määra järjekord.</li>
                                <li>Vajuta <strong>„Lisa kategooria“</strong>.</li>
                            </ol>

                            <ul class="mb-0">
                                <li>Nähtavuse muutmine</li>
                                <li>Järjestuse muutmine nooltega</li>
                                <li>Kategooria muutmine või kustutamine</li>
                            </ul>

                        </div>
                    </div>
                </div>


                {{-- 6. Allergeenid --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseSix">

                            6. Allergeenide haldus
                        </button>
                    </h2>

                    <div id="collapseSix"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <p>Allergeene kasutatakse toitude juures märkimaks võimalikke allergiaallikaid.</p>

                            <ol>
                                <li>Sisesta allergeeni nimi.</li>
                                <li>Sisesta lühend.</li>
                                <li>Määra järjekord.</li>
                                <li>Vajuta <strong>„Lisa allergeen“</strong>.</li>
                            </ol>

                        </div>
                    </div>
                </div>


                {{-- 7. Taustapildid --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseSeven">

                            7. Taustapiltide haldus
                        </button>
                    </h2>

                    <div id="collapseSeven"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <p>Taustapiltide halduses saab üles laadida ja hallata menüü taustapilte.</p>

                            <ol>
                                <li>Vali pildifail (JPG või PNG).</li>
                                <li>Soovi korral tee pilt kohe aktiivseks.</li>
                                <li>Vajuta <strong>„Lae üles“</strong>.</li>
                            </ol>

                        </div>
                    </div>
                </div>


                {{-- 8. Statistika --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseEight">

                            8. Statistika
                        </button>
                    </h2>

                    <div id="collapseEight"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">

                            <p>Statistika leht annab ülevaate menüüdest ja toitudest.</p>

                            <ul>
                                <li>Menüüde koguarv</li>
                                <li>Erinevate toitude arv</li>
                                <li>Kõige populaarsemad toidud</li>
                            </ul>

                            <p class="mb-0">
                                Samuti saab otsida toidu esinemise ajalugu menüüdes.
                            </p>

                        </div>
                    </div>
                </div>


                {{-- 9. Kokkuvõte --}}
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseNine">

                            9. Kokkuvõte
                        </button>
                    </h2>

                    <div id="collapseNine"
                        class="accordion-collapse collapse"
                        data-bs-parent="#manualAccordion">

                        <div class="accordion-body">
                            School Bistro haldusliides võimaldab hallata menüüsid, toite,
                            kategooriaid, allergeene, taustapilte ja statistikat ühes süsteemis.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection