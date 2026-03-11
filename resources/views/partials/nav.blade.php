<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        {{-- Logo / bränd --}}
        <a class="navbar-brand" href="{{ route('menu') }}">
            Bistroo
        </a>

        {{-- Mobile toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav lingid --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- Lisa menüü --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menus.create') ? 'active' : '' }}"
                        href="{{ route('menus.create') }}">
                        Lisa menüü
                    </a>
                </li>

                {{-- Menüü haldus --}}
                <a class="nav-link {{ request()->routeIs('menus.*') ? 'active' : '' }}"
                    href="{{ route('menus.index') }}">
                    Menüü haldus
                </a>

                {{-- Kategooria haldus --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                        href="{{ route('categories.index') }}">
                        Kategooria haldus
                    </a>
                </li>

                {{-- Allergeenide haldus --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('allergens.*') ? 'active' : '' }}"
                        href="{{ route('allergens.index') }}">
                        Allergeenide haldus
                    </a>
                </li>
                {{-- Taustapiltide haldus – kõigile sisse loginud kasutajatele --}}
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('backgrounds.*') ? 'active' : '' }}"
                            href="{{ route('backgrounds.index') }}">
                            Taustapiltide haldus
                        </a>
                    </li>
                @endauth
                {{-- Statistika --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('statistics') ? 'active' : '' }}"
                        href="{{ route('statistics.index') }}">
                        Statistika
                    </a>
                </li>

                {{-- Kasutajate haldus – ainult adminile --}}
                @auth
                    @if (auth()->user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                Kasutajate haldus
                            </a>
                        </li>
                    @endif
                @endauth

                {{-- Menüü tüübid (disabled / tulevik) --}}
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
