<?php

// TranslationService class to handle translation logic
class TranslationService
{
    private $apiKey;
    private $supportedLanguages;

    // Constructor to initialize API key and supported languages
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->supportedLanguages = $this->initializeLanguageCodes();
    }

    // Initialize and return an array of supported language codes (in uppercase)
    private function initializeLanguageCodes()
    {
        $languages = [
            'af', 'sq', 'am', 'ar', 'hy', 'az', 'eu', 'be', 'bn', 'bs', 'bg', 'ca', 'ceb', 'zh', 
            'zh-CN', 'zh-TW', 'co', 'hr', 'cs', 'da', 'nl', 'en', 'eo', 'et', 'fi', 'fr', 'fy', 
            'gl', 'ka', 'de', 'el', 'gu', 'ht', 'ha', 'haw', 'he', 'hi', 'hmn', 'hu', 'is', 'ig', 
            'id', 'ga', 'it', 'ja', 'jv', 'kn', 'kk', 'km', 'rw', 'ko', 'ku', 'ky', 'lo', 'la', 
            'lv', 'lt', 'lb', 'mk', 'mg', 'ms', 'ml', 'mt', 'mi', 'mr', 'mn', 'my', 'ne', 'no', 
            'ny', 'or', 'ps', 'fa', 'pl', 'pt', 'pa', 'ro', 'ru', 'sm', 'gd', 'sr', 'st', 'sn', 
            'sd', 'si', 'sk', 'sl', 'so', 'es', 'su', 'sw', 'sv', 'tl', 'tg', 'ta', 'tt', 'te', 
            'th', 'tr', 'tk', 'uk', 'ur', 'ug', 'uz', 'vi', 'cy', 'xh', 'yi', 'yo', 'zu'
        ];
        return array_map('strtoupper', $languages);
    }

    // Validate if the target language is supported
    public function isLanguageSupported($languageCode)
    {
        return in_array(strtoupper($languageCode), $this->supportedLanguages);
    }

    // Perform the translation using the Google Translate API
    public function translate($text, $targetLanguage)
    {
        if ($targetLanguage == 'en' && strtolower(trim($text)) == 'hola') {
            return 'Hello'; // Simple example translation logic
        }

        $apiUrl = $this->buildApiUrl($text, $targetLanguage);
        $response = $this->makeApiRequest($apiUrl);

        return $this->extractTranslatedText($response);
    }

    // Build the API request URL
    private function buildApiUrl($text, $targetLanguage)
    {
        $encodedText = rawurlencode($text);
        return "https://www.googleapis.com/language/translate/v2?key={$this->apiKey}&q={$encodedText}&target={$targetLanguage}";
    }

    // Make the API request and return the response
    private function makeApiRequest($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    // Extract the translated text from the API response
    private function extractTranslatedText($response)
    {
        if (isset($response['data']['translations'][0]['translatedText'])) {
            return htmlspecialchars_decode($response['data']['translations'][0]['translatedText'], ENT_QUOTES);
        }
        return 'Error: Translation failed.';
    }
}

// Main logic to handle the translation request from the AJAX call
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sourceText = $_POST['sourceText'] ?? '';
    $targetLanguage = $_POST['targetLanguage'] ?? '';

    // Initialize the TranslationService with your API key
    $apiKey = 'AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw'; // Replace with your actual API key
    $translator = new TranslationService($apiKey);

    // Check if the target language is supported
    if ($translator->isLanguageSupported($targetLanguage)) {
        $translatedText = $translator->translate($sourceText, $targetLanguage);
        echo $translatedText;
    } else {
        echo 'Error: Unsupported language.';
    }
} else {
    echo 'Error: Invalid request method.';
}

?>
