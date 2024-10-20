<?php

// TranslationService class handles the core logic of translation
class TranslationService {
    private $apiKey = 'AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw';

    // Method to translate the given text to the target language
    public function translate($text, $targetLanguage) {
        if (empty($text) || empty($targetLanguage)) {
            return "Please provide both text and target language.";
        }

        // Simple example of hardcoded translation
        if ($targetLanguage === 'en' && in_array(strtolower($text), ['hola'])) {
            return "Hello";
        }

        $response = $this->callGoogleTranslateAPI($text, $targetLanguage);
        return $response ?: "Translation failed.";
    }

    // Calls the Google Translate API and returns the translated text
    private function callGoogleTranslateAPI($text, $targetLanguage) {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $this->apiKey .
            '&q=' . rawurlencode($text) . '&target=' . $targetLanguage;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            curl_close($curl);
            return null;
        }

        $decodedResponse = json_decode($response, true);
        curl_close($curl);

        if (isset($decodedResponse['data']['translations'][0]['translatedText'])) {
            return htmlspecialchars_decode($decodedResponse['data']['translations'][0]['translatedText'], ENT_QUOTES);
        }

        return null;
    }
}

// InputValidator class to validate incoming POST data
class InputValidator {
    public static function validate($input) {
        return htmlspecialchars(trim($input));
    }
}

// Main execution logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sourceText = InputValidator::validate($_POST['sourceText'] ?? '');
    $targetLanguage = InputValidator::validate($_POST['targetLanguage'] ?? '');

    $translator = new TranslationService();
    echo $translator->translate($sourceText, $targetLanguage);
} else {
    echo "Invalid request method.";
}
?>
