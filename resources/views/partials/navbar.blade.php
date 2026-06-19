<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between flex-wrap">
        {{-- Logo --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-600 flex items-center justify-center text-white font-bold text-xl">❤</div>
            <div>
                <h1 class="font-bold text-xl text-slate-900">AAROGYA</h1>
                <p class="text-xs text-slate-500">{{ __('messages.footer_tagline') }}</p>
            </div>
        </div>

        {{-- Navigation Links --}}
        <div class="hidden md:flex gap-8 font-medium">
            <a href="{{ url('/') }}" class="hover:text-cyan-600 {{ request()->is('/') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}">
                {{ __('messages.home') }}
            </a>
            <a href="{{ route('symptom.checker') }}" class="hover:text-cyan-600 {{ request()->routeIs('symptom.checker') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}">
                {{ __('messages.symptom_checker') }}
            </a>
            <a href="{{ route('doctors') }}" class="hover:text-cyan-600 {{ request()->routeIs('doctors') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}">
                {{ __('messages.doctors') }}
            </a>
            <a href="{{ route('services') }}" class="hover:text-cyan-600 {{ request()->routeIs('services') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}">
                {{ __('messages.services') }}
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-cyan-600 {{ request()->routeIs('dashboard') ? 'text-cyan-600 border-b-2 border-cyan-600' : '' }}">
                    {{ __('messages.dashboard') }}
                </a>
            @endauth
        </div>

        {{-- Right Side: Language Switcher + Auth --}}
        <div class="flex items-center gap-4">
            {{-- Language Switcher --}}
            <div class="flex items-center gap-2 text-sm">
                <form method="POST" action="{{ route('language.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="ne">
                    <button type="submit" 
                        class="px-2 py-1 rounded hover:bg-gray-100 transition {{ app()->getLocale() == 'ne' ? 'font-bold text-cyan-600' : 'text-slate-600' }}">
                        🇳🇵 {{ __('messages.nepali') }}
                    </button>
                </form>
                <span class="text-gray-300">|</span>
                <form method="POST" action="{{ route('language.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" 
                        class="px-2 py-1 rounded hover:bg-gray-100 transition {{ app()->getLocale() == 'en' ? 'font-bold text-cyan-600' : 'text-slate-600' }}">
                        🇬🇧 {{ __('messages.english') }}
                    </button>
                </form>
            </div>

            {{-- Auth Buttons --}}
            @auth
                <span class="text-slate-700 text-sm hidden md:inline">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600 transition">
                        {{ __('messages.logout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-cyan-600 text-white px-5 py-3 rounded-xl hover:bg-cyan-700 transition">
                    {{ __('messages.start') }}
                </a>
            @endauth
        </div>
    </div>
</nav>