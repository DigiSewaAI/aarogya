<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LucianoTonet\GroqPHP\Groq;

class SymptomCheckerController extends Controller
{
    public function index()
    {
        return view('simple-checker');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string|min:3',
        ]);

        $symptoms = $request->input('symptoms');
        $duration = $request->input('duration', 'उल्लेख छैन');
        $bodyPart = $request->input('body_part', 'उल्लेख छैन');

        // भाषा पहिचान
        $language = $this->detectLanguage($symptoms);

        // भाषा अनुसार Groq निर्देशन
        $languageInstruction = $this->getLanguageInstruction($language);
        $systemMessage = $this->getSystemMessage($language);

        // Prompt बनाउने (भाषा-अनुकूल)
        $prompt = $this->buildPrompt($symptoms, $duration, $bodyPart, $languageInstruction);

        try {
            $groq = new Groq(env('GROQ_API_KEY'));
            
            $response = $groq->chat()->completions()->create([
                'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2,
                'max_tokens' => 700,
            ]);

            $aiResponse = $response['choices'][0]['message']['content'];

            // हेडिङलाई मिक्स (Sambhawit Rog, आदि) मा बदल्ने
            $aiResponse = $this->translateHeadersToMixed($aiResponse);

            // थप सफाई
            $aiResponse = str_replace(['*', '**'], '', $aiResponse);
            $aiResponse = preg_replace('/\([^)]*\)/', '', $aiResponse);
            $aiResponse = preg_replace('/:\s*:/', ':', $aiResponse); // extra colon remove
            $aiResponse = preg_replace('/\s+/', ' ', $aiResponse);
            $aiResponse = trim($aiResponse);

            return view('simple-checker', [
                'result' => $aiResponse,
                'oldSymptoms' => $symptoms,
            ]);

        } catch (\Exception $e) {
            return $this->fallbackAnalysis($symptoms, $duration, $bodyPart);
        }
    }

    /**
     * भाषा पहिचान: 'devanagari', 'pure_english', 'mixed'
     */
    private function detectLanguage($text)
    {
        if (preg_match('/[\x{0900}-\x{097F}]/u', $text)) {
            return 'devanagari';
        } elseif (preg_match('/^[a-zA-Z0-9\s\.,!?\'\"]+$/', $text)) {
            return 'pure_english';
        } else {
            return 'mixed';
        }
    }

    /**
     * भाषा अनुसार Groq लाई निर्देशन
     */
    private function getLanguageInstruction($language)
    {
        if ($language === 'devanagari') {
            return 'पूर्ण रूपमा नेपाली भाषा (देवनागरी) मा मात्र जवाफ दिनुहोस्। हेडिङ र बुलेट सबै नेपालीमा हुनुपर्छ।';
        } elseif ($language === 'pure_english') {
            return 'पूर्ण रूपमा अंग्रेजी भाषामा मात्र जवाफ दिनुहोस्। हेडिङ र बुलेट सबै अंग्रेजीमा हुनुपर्छ।';
        } else { // mixed
            return 'प्रयोगकर्ताले जुन शैलीमा लेखेको छ (रोमन नेपाली+अंग्रेजी मिक्स), त्यही शैलीमा जवाफ दिनुहोस्। उदाहरण: "tapaai lai viral fever huna sakchha, aankha raatopani ra jwaro cha."';
        }
    }

    /**
     * System message – भाषा अनुसार
     */
    private function getSystemMessage($language)
    {
        $base = 'You are a concise, helpful AI doctor. Give short, non-repetitive, structured medical advice. Maximum 2-3 diseases. No "Precautions" section - include precautions inside Home Remedies. Use "-" for bullets only. No extra colons. No repetition.';
        if ($language === 'devanagari') {
            return $base . ' Respond in pure Nepali (Devanagari) only.';
        } elseif ($language === 'pure_english') {
            return $base . ' Respond in pure English only.';
        } else {
            return $base . ' Respond in the same mixed style as the user (Romanized Nepali + English).';
        }
    }

    /**
     * Prompt builder
     */
    private function buildPrompt($symptoms, $duration, $bodyPart, $instruction)
    {
        return "तपाईं एक सहायक AI डाक्टर हुनुहुन्छ। {$instruction}

**महत्वपूर्ण नियमहरू:**
1. अधिकतम २-३ वटा रोगको नाम मात्र लेख्नुहोस्
2. कुनै पनि बुँदा दोहोर्याउनुहोस् (पुनरावृत्ति नगर्नुहोस्)
3. प्रत्येक सेक्सनमा २-४ वटा बुँदा मात्र पर्याप्त छ
4. हेडिङमा `:` एक पटक मात्र प्रयोग गर्नुहोस्
5. 'Savadhani' सेक्सन नदिनुहोस् (घरेलु उपचारमा नै समावेश गर्नुहोस्)

कृपया तलको ढाँचामा जवाफ दिनुहोस्। Bullet points को लागि `-` मात्र प्रयोग गर्नुहोस्।

Possible Disease (सम्भावित रोग):
- रोगको नाम १
- रोगको नाम २

Home Remedies (घरेलु उपचार):
- सुझाव १
- सुझाव २
- सुझाव ३

When to See a Doctor (डाक्टरलाई कहिले भेट्ने):
- अवस्था १
- अवस्था २
- अवस्था ३

लक्षण: {$symptoms}
अवधि: {$duration}
दुख्ने भाग: {$bodyPart}";
    }

    /**
     * हेडिङलाई मिक्स (Sambhawit Rog आदि) मा बदल्ने
     */
    private function translateHeadersToMixed($text)
    {
        $replacements = [
            '/Possible Disease:?\s*/i' => 'Sambhawit Rog: ',
            '/Home Remedies:?\s*/i' => 'Gharelu Upachar: ',
            '/When to See a Doctor:?\s*/i' => 'Daktar lai kahile bhetne: ',
            '/Precautions:?\s*/i' => '',
            '/Symptoms:?\s*/i' => 'Lakshan: ',
            '/Duration:?\s*/i' => 'Awadhi: ',
            '/Affected Area:?\s*/i' => 'Prabhavit Bhag: ',
        ];
        $text = preg_replace(array_keys($replacements), array_values($replacements), $text);
        // अतिरिक्त अंग्रेजी हेडिङ पनि बदल्न
        $text = str_ireplace('Possible Disease', 'Sambhawit Rog', $text);
        $text = str_ireplace('Home Remedies', 'Gharelu Upachar', $text);
        $text = str_ireplace('When to See a Doctor', 'Daktar lai kahile bhetne', $text);
        $text = str_ireplace('Precautions', '', $text);
        return $text;
    }

    /**
     * फलब्याक (API अनुपलब्ध भएमा)
     */
    private function fallbackAnalysis($symptoms, $duration, $bodyPart)
    {
        $symptomsLower = strtolower($symptoms);
        $result = '';

        if (str_contains($symptomsLower, 'ज्वरो') || str_contains($symptomsLower, 'fever')) {
            $result = 'भाइरल ज्वरोको सम्भावना। आराम गर्नुहोस्, तातो पानी पिउनुहोस्।';
        } elseif (str_contains($symptomsLower, 'टाउको') || str_contains($symptomsLower, 'headache')) {
            $result = 'टाउको दुखाइ – पानी पिउनुहोस्, आराम गर्नुहोस्।';
        } elseif (str_contains($symptomsLower, 'खोकी') || str_contains($symptomsLower, 'cough')) {
            $result = 'रुघाखोकी – न्यानो पानी पिउनुहोस्, मास्क लगाउनुहोस्।';
        } else {
            $result = 'कृपया डाक्टरलाई प्रत्यक्ष भेट्नुहोस्।';
        }

        return view('simple-checker', [
            'result' => $result . ' (API अनुपलब्ध – साधारण जवाफ)',
            'oldSymptoms' => $symptoms,
        ]);
    }
}