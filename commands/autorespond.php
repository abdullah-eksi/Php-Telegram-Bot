
<?php


function getAIResponse($user_input) {

$API_KEY = "";
    // API URL'si
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.0-pro:generateContent?key=$API_KEY";

    // JSON verisi oluşturma
    $data = json_encode(array(
        "contents" => array(
            array(
                "role" => "user",
                "parts" => array(
                    array(
                        "text" => $user_input
                    )
                )
            )
        ),
        "generationConfig" => array(
            "temperature" => 0.9,
            "topK" => 1,
            "topP" => 1,
            "maxOutputTokens" => 2048,
            "stopSequences" => []
        ),
        "safetySettings" => array(
            array(
                "category" => "HARM_CATEGORY_HARASSMENT",
                "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
            ),
            array(
                "category" => "HARM_CATEGORY_HATE_SPEECH",
                "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
            ),
            array(
                "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
            ),
            array(
                "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                "threshold" => "BLOCK_MEDIUM_AND_ABOVE"
            )
        )
    ));

    // cURL başlatma
    $ch = curl_init();

    // cURL seçeneklerini ayarlama
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // cURL işlemini gerçekleştirme
    $response = curl_exec($ch);

    // cURL işlem sonucunu kontrol etme
    if ($response === false) {
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // Yanıtı döndürme
        return $response;
    }

    // cURL işlemini kapatma
    curl_close($ch);
}


function handleAutorespondCommand($telegram, $params, $message)
{





    $chatId =  $message['chat']['id'];
    $text = $message['text'];
    $user = $message['from']['first_name'];




    $qa_pairs =[
      'selam'=>'Selam [user_name]. Nasılsın? Sana nasıl yardımcı olabilirim?',
      'merhaba'=>'Merhaba! Size nasıl yardımcı olabilirim?',
      'nasılsın'=>'İyiyim, teşekkür ederim. Sizinle nasıl ilgilenebilirim?',
      'iyiyim'=>'Ne güzel! Size nasıl yardımcı olabilirim?',
      'teşekkür ederim'=>'Rica ederim. Başka bir şey sorabilirsiniz.',
      'adın ne'=>'Benim adım AeXpbot. Size nasıl yardımcı olabilirim?',
      'seni kim yaptı'=>'Ben Abdullah Ekşi tarafından geliştirildim. Size nasıl yardımcı olabilirim?',
      'ne işe yararsın'=>'Ben, sizin sorularınızı yanıtlamak ve size yardımcı olmak için buradayım. Size nasıl yardımcı olabilirim?',
      'hangi dilleri konuşabiliyorsun'=>'Türkçe  dilinde iletişim kurabilirim.',
      'hangi konularda yardımcı olabilirsin'=>'Genel olarak herhangi bir konuda size yardımcı olmaya çalışabilirim.',
      'bugün hava nasıl'=>'Bugün hava nasıl olduğuna dair bilgi almak istiyorsanız lütfen Havadurumu komutunu kullanın',
      'hava durumu'=>' hava durumu Öğrenmek İstersen Havadurumu Komutunu Kullanabilirsin',
      'istanbul kaç nüfuslu'=>'İstanbul\'un nüfusu yaklaşık olarak 15 milyon civarındadır.',
      'ankara nerede'=>'Ankara, Türkiye\'nin başkenti ve İç Anadolu Bölgesi\'nde yer almaktadır.',
      'türkiye hangi kıtada'=>'Türkiye, hem Avrupa hem de Asya kıtalarında bulunur. Bu nedenle Avrasya olarak adlandırılır.',
      'neden yaşamaya değer'=>'Yaşamak, keşfetmek, öğrenmek ve sevmek için birçok neden sunar. Herkesin yaşamaya değer bulduğu farklı şeyler vardır.',
      'gezegenler kaç tane'=>'Şu anda güneş sisteminde bilinen 8 gezegen bulunmaktadır.',
      'futbol dünyası'=>'Futbol, dünya genelinde en popüler spor dallarından biridir. Dünya Kupası gibi büyük etkinliklerle tanınır.',
      'beyin nasıl çalışır'=>'Beyin, vücudunuzun kontrol merkezidir ve karmaşık bir şekilde çalışır. Duygularınızı, düşüncelerinizi ve hareketlerinizi düzenler.',
      'hangi kitapları tavsiye edersiniz'=>'Tavsiye edebileceğim birçok harika kitap var, ama sizin ilgi alanlarınızı bilmeden bir öneri yapamam. Hangi tür kitaplar ilginizi çekiyor?',
      'hangi filmi izlemeliyim'=>'İzleyebileceğiniz harika filmler var. İlgi alanlarınıza göre size birkaç öneride bulunabilirim. Hangi tür filmleri seversiniz?',
      'en sevdiğiniz yemek nedir'=>'En sevdiğim yemek, herkesin damak zevkine göre değişir ama genellikle pizza ve makarna gibi yiyecekleri severim. Sizin en sevdiğiniz yemek nedir?',
      'hayatın anlamı nedir'=>'Hayatın anlamı, insanlar arasında uzun zamandır tartışılan bir konudur. Herkesin hayatıyla ilgili farklı görüşleri ve inançları vardır.',
      'bana bir şaka anlat'=>'Tabii ki! İşte bir şaka: Bir adam bir bara girer ve "Bir içki alayım" der. Bar çıkar!',
      'hoşgeldin'=>'Teşekkür ederim! Size nasıl yardımcı olabilirim?',
      'günaydın'=>'Günaydın! Nasıl yardımcı olabilirim?',
      'iyi geceler'=>'İyi geceler! Başka bir sorunuz varsa sormaktan çekinmeyin.',
      'ne haber'=>'Ben bir yapay zeka olduğum için haberim yok ama size nasıl yardımcı olabilirim?',
      'neredesin'=>'Ben bir sanal asistanım, her zaman buradayım! Sizinle nasıl yardımcı olabilirim?',

  ];
    $user_message = preg_replace("/[^a-zA-Z0-9ğüşıöçĞÜŞİÖÇ\s]+/", "", strtolower(trim($text)));

    $best_match = '';
    $best_similarity = 0;
    foreach ($qa_pairs as $question => $response) {
        similar_text($question, $user_message, $similarity);
        if ($similarity > $best_similarity) {
            $best_similarity = $similarity;
            $best_match = $response;
        }
    }

     if ($best_similarity >= 50) {
        // Tanımlı bir soru varsa, direkt olarak cevabı gönder
        $response = $best_match;
		 
    } else {
        // Tanımlı sorulardan uygun cevap bulunamazsa, yapay zeka API'sinden cevap al
        $ai_response = getAIResponse($text);
        $ai_response_decoded = json_decode($ai_response, true);
        $response = $ai_response_decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

        // Eğer yapay zeka bir cevap döndürmediyse veya cevap alınamadıysa
        if (!$response) {
            $response = "Üzgünüm, bu konuda bir bilgim yok.";
        }
    }
  $response = str_replace('[user_name]', $user, $best_match);
    // Kullanıcıya cevabı gönder
    $telegram->sendMessage([
        'chat_id' => $chatId,
        'text' => $response
    ]);
}

?>
