<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAROGYA - {{ __('messages.symptom_title') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        .hero-bg { background: radial-gradient(circle at top left, #67e8f9 0%, transparent 35%), radial-gradient(circle at bottom right, #93c5fd 0%, transparent 35%), #f8fafc; }
        /* ✅ Social icons fix – apply fill color from text color */
        .social-icon { fill: currentColor; width: 24px; height: 24px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">

    @include('partials.navbar')

    <section class="hero-bg overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 py-16">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-100 text-cyan-700 font-medium mb-4">
                    {{ __('messages.symptom_badge') }}
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-900">
                    {!! __('messages.symptom_heading') !!}
                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto">
                    {{ __('messages.symptom_subtitle') }}
                </p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-slate-100">
                <form method="POST" action="{{ route('symptom.analyze') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-slate-700 font-semibold mb-2">{{ __('messages.symptom_label') }}</label>
                        <textarea name="symptoms" rows="4" required class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="{{ __('messages.symptom_placeholder') }}">{{ old('symptoms') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-2">{{ __('messages.symptom_duration_label') }}</label>
                        <select name="duration" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-cyan-500">
                            <option value="1">{{ __('messages.symptom_duration_1') }}</option>
                            <option value="2">{{ __('messages.symptom_duration_2') }}</option>
                            <option value="3">{{ __('messages.symptom_duration_3') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 font-semibold mb-2">{{ __('messages.symptom_body_label') }}</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_head') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_head') }}</button>
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_neck') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_neck') }}</button>
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_stomach') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_stomach') }}</button>
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_chest') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_chest') }}</button>
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_back') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_back') }}</button>
                            <button type="button" onclick="setBodyPart('{{ __('messages.symptom_body_legs') }}')" class="bg-cyan-50 p-2 rounded-xl hover:bg-cyan-100 transition">{{ __('messages.symptom_body_legs') }}</button>
                        </div>
                        <input type="hidden" name="body_part" id="body_part_input">
                        <p class="text-xs text-slate-400 mt-2">{{ __('messages.symptom_body_selected') }}: <span id="selected_body_part" class="font-medium text-cyan-700">{{ __('messages.symptom_none') }}</span></p>
                    </div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        {{ __('messages.symptom_analyze_button') }}
                    </button>
                </form>

                @if(isset($result))
                    <div class="mt-10 pt-6 border-t border-slate-200">
                        <h3 class="text-2xl font-bold text-slate-800 flex items-center gap-2">{{ __('messages.symptom_ai_suggestion') }}</h3>
                        <div class="mt-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6">
                            <p class="text-slate-700 text-lg leading-relaxed">{{ $result }}</p>
                            @if(isset($nextStep))
                                <div class="mt-4 p-3 bg-white/50 rounded-xl">
                                    <span class="font-semibold">{{ __('messages.symptom_next_step') }}:</span> {{ $nextStep }}
                                </div>
                            @endif
                            <div class="mt-6 flex gap-4">
                                <a href="{{ route('doctors') }}" class="inline-block bg-cyan-600 text-white px-5 py-2 rounded-xl hover:bg-cyan-700 transition">{{ __('messages.symptom_find_doctor') }}</a>
                                <a href="{{ route('symptom.checker') }}" class="inline-block border border-slate-300 px-5 py-2 rounded-xl hover:bg-slate-100 transition">{{ __('messages.symptom_try_again') }}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-10 p-6 bg-slate-50 rounded-2xl text-center text-slate-500 italic border border-slate-200">
                        {{ __('messages.symptom_ai_placeholder') }}
                    </div>
                @endif

                <div class="mt-6 text-xs text-slate-400 text-center">
                    {{ __('messages.symptom_disclaimer') }}
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
        function setBodyPart(part) {
            document.getElementById('body_part_input').value = part;
            document.getElementById('selected_body_part').innerText = part;
        }
    </script>
</body>
</html>