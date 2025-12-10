<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        {{-- Logo / bränd --}}
        <a class="navbar-brand" href="{{ route('menu') }}">
            Bistroo
        </a>

        {{-- Mobile toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav lingid --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        Avaleht
                    </a>
                </li>

                {{-- Menüü tüübid (kui sul on vastav route) --}}
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/menu-types*') ? 'active' : '' }}"
                       href="{{ route('menu-types.index') ?? '#' }}">
                        Menüü tüübid
                    </a>
                </li> --}}

                {{-- Siia saab hiljem lisada nt: Tänane menüü, Seaded jne --}}
            </ul>

            {{-- Parempoolne osa: kasutaja / login --}}
            <ul class="navbar-nav mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            {{ Auth::user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">
                                Logi välja
                            </button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Logi sisse</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
