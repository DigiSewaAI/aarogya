<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between flex-wrap">
        {{-- Logo --}}
        <div class="flex items-center gap-3">
            {{-- ✅ Logo Image --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="AAROGYA Logo" class="h-10 w-auto">
                <div>
                    <h1 class="font-bold text-xl text-slate-900">AAROGYA</h1>
                    <p class="text-xs text-slate-500">{{ __('messages.footer_tagline') }}</p>
                </div>
            </a>
        </div>

        {{-- Navigation Links --}}
        <div class="hidden md:flex gap-8 font-medium">
            <a href="{{ url('/') }}" class="hover:text-cyan-600 transition {{ request()->is('/') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->is('/') ? 'aria-current="page"' : '' }}>
                {{ __('messages.home') }}
            </a>
            <a href="{{ route('symptom.checker') }}" class="hover:text-cyan-600 transition {{ request()->routeIs('symptom.checker') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->routeIs('symptom.checker') ? 'aria-current="page"' : '' }}>
                {{ __('messages.symptom_checker') }}
            </a>
            <a href="{{ route('doctors') }}" class="hover:text-cyan-600 transition {{ request()->routeIs('doctors') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->routeIs('doctors') ? 'aria-current="page"' : '' }}>
                {{ __('messages.doctors') }}
            </a>
            <a href="{{ route('clinics') }}" class="hover:text-cyan-600 transition {{ request()->routeIs('clinics*') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->routeIs('clinics*') ? 'aria-current="page"' : '' }}>
                {{ __('messages.clinics') }}
            </a>
            <a href="{{ route('services') }}" class="hover:text-cyan-600 transition {{ request()->routeIs('services') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->routeIs('services') ? 'aria-current="page"' : '' }}>
                {{ __('messages.services') }}
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-cyan-600 transition {{ request()->routeIs('dashboard') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}" {{ request()->routeIs('dashboard') ? 'aria-current="page"' : '' }}>
                    {{ __('messages.dashboard') }}
                </a>
            @endauth
        </div>

        {{-- Right Side: Language Switcher + Auth --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 text-sm">
                <form method="POST" action="{{ route('language.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="ne">
                    <button type="submit" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 transition {{ app()->getLocale() == 'ne' ? 'font-semibold text-cyan-600 bg-cyan-50' : 'text-slate-600' }}">
                        🇳🇵 {{ __('messages.nepali') }}
                    </button>
                </form>
                <span class="text-slate-300">|</span>
                <form method="POST" action="{{ route('language.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 transition {{ app()->getLocale() == 'en' ? 'font-semibold text-cyan-600 bg-cyan-50' : 'text-slate-600' }}">
                        🇬🇧 {{ __('messages.english') }}
                    </button>
                </form>
            </div>

            @auth
                <span class="text-slate-700 text-sm hidden md:inline font-medium">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl transition text-sm font-medium">
                        {{ __('messages.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-xl transition font-medium">
                    {{ __('messages.start') }}
                </a>
            @endauth
        </div>
    </div>
</nav>