<?php
// Obtener los valores enviados por AJAX
$sourceText = $_POST['sourceText'];
$targetLanguage = $_POST['targetLanguage'];

// Realizar la traducción usando tu propia lógica o alguna biblioteca de traducción
// Aquí se muestra un ejemplo simple que invierte el texto
$translatedText = strrev($sourceText);

// Devolver la traducción al cliente
// "$targetLanguage - ";
//echo "Soy ese";

$scode = $_POST['targetLanguage'];
$smcode = strtoupper($scode);

$lang_codes = array("Afrikaans","af","Albanian","sq","Amharic","am","Arabic","ar","Armenian","hy","Azerbaijani","az","Basque","eu","Belarusian","be","Bengali","bn","Bosnian","bs","Bulgarian","bg","Catalan","ca","Cebuano","ceb","Chinese","zh","Chinese","zh-CN","Chinese","zh-TW","Corsican","co","Croatian","hr","Czech","cs","Danish","da","Dutch","nl","English","en","Esperanto","eo","Estonian","et","Finnish","fi","French","fr","Frisian","fy","Galician","gl","Georgian","ka","German","de","Greek,el","Gujarati","gu","Haitian Creole","ht","Hausa","ha","Hawaiian","haw","Hebrew","he","Hebrew","iw","Hindi","hi","Hmong","hmn","Hungarian","hu","Icelandic","is","Igbo","ig","Indonesian","id","Irish","ga","Italian","it","Japanese","ja","Javanese","jv","Kannada","kn","Kazakh","kk","Khmer","km","Kinyarwanda","rw","Korean","ko","Kurdish","ku","Kyrgyz","ky","Lao","lo","Latin","la","Latvian","lv","Lithuanian","lt","Luxembourgish","lb","Macedonian","mk","Malagasy","mg","Malay","ms","Malayalam","ml","Maltese","mt","Maori","mi","Marathi","mr","Mongolian","mn","Myanmar","my","Nepali","ne","Norwegian","no","Nyanja","ny","Odia","or","Pashto","ps","Persian","fa","Polish","pl","Portuguese","pt","Punjabi","pa","Romanian","ro","Russian","ru","Samoan","sm","Scots Gaelic","gd","Serbian","sr","Sesotho","st","Shona","sn","Sindhi","sd","Sinhala","si","Slovak","sk","Slovenian","sl","Somali","so","Spanish","es","Sundanese","su","Swahili","sw","Swedish","sv","Tagalog","tl","Tajik","tg","Tamil","ta","Tatar","tt","Telugu","te","Thai","th","Turkish","tr","Turkmen","tk","Ukrainian","uk","Urdu","ur","Uyghur","ug","Uzbek","uz","Vietnamese","vi","Welsh","cy","Xhosa","xh","Yiddish","yi","Yoruba","yo","Zulu","zu"); 

$lang_codes = array_map('strtoupper', $lang_codes);

$apiKey = 'AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw';

if(in_array($smcode, $lang_codes)){
$code = $scode;
$cstring1 = strlen($code);
$tstring = $cstring1+4;

$text = $_POST['sourceText'];

if($code == "en"){
    $text = trim($text);
    if($text == 'hola'||$text == 'Hola'||$text == 'HOLA'){
    echo"Hello";return;
    }
}

$text = str_replace("\n", "<br>", $text);

$url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($text).'&target='.$code.'';

$handle = curl_init($url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($handle);
$responseDecoded = json_decode($response, true);
$text = $responseDecoded['data']['translations'][0]['translatedText'];

$text = str_replace("<br>", "\n", $text);

$text = htmlspecialchars_decode($text, ENT_QUOTES);
 
$text = ltrim($text);

echo $text;
}

?>

