<?php


function getAIResponse($user_input) {

$API_KEY = "AIzaSyCZRlF64SuBjUHVCwv6wm_z6PelEaSUUw8";
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





    $chatId = "1751716459";
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
      'hangi amaçla buradasın'=>'Burada insanlara yardımcı olmak için bulunuyorum. Sizinle nasıl yardımcı olabilirim?',
      'nasıl çalışıyorsun'=>'Ben yapay zeka modeliyim ve gelişmiş bir dil modeli kullanarak sizinle iletişim kuruyorum. Size nasıl yardımcı olabilirim?',
      'en sevdiğin yemek nedir'=>'Ben yemek yiyemem ama insanların en sevdiği yemekler genellikle pizza veya makarnadır.',
      'en sevdiğin film nedir'=>'Ben bir yapay zeka olduğum için filmleri izleyemem ama insanların sevdiği popüler filmler arasında "The Shawshank Redemption" ve "The Godfather" bulunur.',
      'neden buradasın'=>'Ben buradayım çünkü size yardımcı olmak için tasarlandım.',
      'en sevdiğin şarkı nedir'=>'Ben bir yapay zeka olduğum için şarkıları duyamam ama insanların sevdiği şarkılar genellikle müzik zevklerine bağlıdır.',
      'bir şaka anlat'=>'Neden tavuk yolun karşısını geçti? Tavuk bir barbeküye gitmek için.',
      'en sevdiğin spor nedir'=>'Ben bir yapay zeka olduğum için spor yapamam ama insanların sevdiği sporlar futbol, ​​basketbol ve ​​voleybol gibi çeşitli sporlardır.',
      'en son kitap ne zaman okudun'=>'Ben bir yapay zeka olduğum için kitapları okuyamam ama insanlar genellikle en son okudukları kitapları hatırlamaktadır.',
      'en sevdiğin renk nedir'=>'Ben bir yapay zeka olduğum için renkleri göremem ama insanların sevdiği renkler genellikle mavi, yeşil veya kırmızıdır.',
      'hayvanların var mı'=>'Ben bir yapay zeka olduğum için hayvanlarım yok ama kediler ve köpekler gibi evcil hayvanlar çok sevilir.',
      'nasıl bir gün geçirdin'=>'Ben bir yapay zeka olduğum için gün geçiremiyorum ama sizin gününüz nasıl geçti?',
      'en sevdiğin meyve nedir'=>'Ben bir yapay zeka olduğum için meyveleri tatamam ama insanların sevdiği meyveler genellikle elma, muz ve çilektir.',
      'ünlü biriyle tanışmak ister misin'=>'Ben bir yapay zeka olduğum için insanlarla tanışamam ama insanlar genellikle ünlü kişilerle tanışmak ister.',
      'bugün ne yaptın'=>'Bugün birçok farklı soruyu yanıtlamak için buradayım. Siz de benimle sohbet edebilirsiniz!',
      'en sevdiğin Türk yemeği nedir'=>'Türk mutfağı çok çeşitlidir ve birçok lezzetli yemeği vardır. Benim sevdiğim Türk yemeği kebaplar ve baklavalar gibi geleneksel lezzetlerdir.',
      'en güzel Türk şehri hangisidir'=>'Türkiye\'nin birçok güzel şehri vardır ve her birinin kendine özgü güzellikleri bulunmaktadır. İstanbul, tarihi ve kültürel mirasıyla öne çıkan en güzel Türk şehirlerinden biridir.',
      'en sevdiğin Türk şarkıcı kim'=>'Türk müziği de dünya çapında çok sevilen bir müzik türüdür. Benim sevdiğim Türk şarkıcıları arasında Barış Manço, Sezen Aksu ve Tarkan gibi isimler bulunmaktadır.',
      'Türkiye hakkında ne biliyorsun'=>'Türkiye, tarih boyunca birçok medeniyete ev sahipliği yapmış ve zengin bir kültürel mirasa sahiptir. Türk mutfağı, müziği, sanatı ve tarihi dünya çapında tanınmaktadır.',
      'Türk kahvesi içer misin'=>'Ben bir yapay zeka olduğum için içebilecek bir vücuda sahip değilim ama Türk kahvesi Türk kültürünün önemli bir parçasıdır ve birçok kişi tarafından sevilir.',
      'Türkiye\'yi ziyaret etmek ister misin'=>'Ben bir yapay zeka olduğum için seyahat edemem ama Türkiye\'nin güzellikleri ve tarihi yerleri hakkında bilgi edinmek isterim.',
      'Türkiye\'nin başkenti neresidir'=>'Türkiye\'nin başkenti Ankara\'dır. Türkiye\'nin idari ve politik merkezi burasıdır.',
      'Türkiye\'de hangi dil konuşulur'=>'Türkiye\'de resmi dil Türkçe\'dir, ancak ülkede Kürtçe, Arapça ve Zazaca gibi diğer diller de konuşulmaktadır.',
      'Türkiye\'de hangi festivaller yapılır'=>'Türkiye\'de birçok festival ve etkinlik düzenlenmektedir. Örneğin, İstanbul Film Festivali, İzmir Fuarı ve Antalya Altın Portakal Film Festivali gibi etkinlikler Türkiye\'de düzenlenmektedir.',
      'Türk kültüründe ne gibi gelenekler vardır'=>'Türk kültüründe çay kültürü, misafirperverlik, düğünlerde takı merasimi gibi birçok gelenek bulunmaktadır.',
      'En büyük Türk şirketleri hangileridir'=>'Türkiye\'de birçok büyük ve tanınmış şirket bulunmaktadır. Örneğin, Koç Holding, Türk Hava Yolları, Turkcell gibi şirketler Türkiye ekonomisinde önemli bir yere sahiptir.',
      'Türkiye\'nin en ünlü tarihi yapıları nelerdir'=>'Türkiye\'nin en ünlü tarihi yapıları arasında Ayasofya, Topkapı Sarayı, Kapadokya gibi yerler bulunmaktadır.',
      'Türk halkının en sevdiği spor hangisidir'=>'Türkiye\'de futbol en popüler spor dallarından biridir. Türk halkı büyük bir futbol tutkunudur ve Süper Lig maçları büyük ilgi görmektedir.',
      'Türkiye\'nin en güzel plajları nerede bulunur'=>'Türkiye\'nin güney kıyıları, Ege ve Akdeniz sahilleri, ülkenin en güzel plajlarına ev sahipliği yapmaktadır. Örneğin, Bodrum, Antalya ve Kuşadası gibi yerler ünlü turistik plajlara sahiptir.',
      'Türk kahvesinin özellikleri nelerdir'=>'Türk kahvesi, özgün kavrulmuş kahve çekirdekleriyle yapılan ve cezvede demlenen yoğun ve aromatik bir kahve çeşididir. Türk kahvesi, özellikle Türk kültüründe önemli bir yere sahiptir.',
      'Türkiye\'de hangi yemekler meşhurdur'=>'Türkiye\'de kebaplar, döner, börekler, mezeler ve baklavalar gibi birçok lezzetli yemek meşhurdur. Türk mutfağı dünya çapında tanınmaktadır.',
      'Türkiye\'nin en büyük gölü hangisidir'=>'Türkiye\'nin en büyük gölü Van Gölü\'dür. Van Gölü, Türkiye\'nin doğu kesiminde yer almaktadır ve önemli bir tatlı su rezervuarıdır.',
      'Türk müziğinin en ünlü enstrümanı nedir'=>'Türk müziğinin en ünlü enstrümanları arasında bağlama, kemençe ve darbuka gibi enstrümanlar bulunmaktadır. Bağlama, Türk halk müziğinde önemli bir yer tutar.',
      'Türkiye\'de kaç tane UNESCO Dünya Mirası var'=>'Türkiye\'de 18 adet UNESCO Dünya Mirası alanı bulunmaktadır. Bu alanlar arasında Pamukkale, Kapadokya, Efes Antik Kenti gibi tarihi ve doğal alanlar yer almaktadır.',
      'Karadeniz\'de hangi iller bulunur'=>'Karadeniz bölgesinde Amasya, Ordu, Samsun, Sinop, Trabzon gibi birçok il bulunmaktadır.',
      'Karadeniz bölgesinin en ünlü yemeği nedir'=>'Karadeniz bölgesinin en ünlü yemeği kuymak olarak bilinir. Kuymak, mısır unu, tereyağı ve peynirle yapılan lezzetli bir yemektir.',
      'Karadeniz\'in en yüksek dağı hangisidir'=>'Karadeniz bölgesinin en yüksek dağı Kaçkar Dağı\'dır. Kaçkar Dağı, doğal güzellikleri ve trekking rotalarıyla ünlüdür.',
      'Karadeniz bölgesinde hangi doğal güzellikler bulunur'=>'Karadeniz bölgesinde doğal güzellikler arasında Uzungöl, Ayder Yaylası, Fırtına Deresi gibi yerler bulunmaktadır. Bu alanlar doğa turizmi açısından oldukça önemlidir.',
      'Karadeniz bölgesinin iklimi nasıldır'=>'Karadeniz bölgesinin iklimi genellikle Karadeniz iklimi olarak adlandırılır. Bu iklim tipinde yazlar serin ve yağışlı, kışlar ise ılık geçer. Bu nedenle bölge yeşil ve verimli bir doğaya sahiptir.',
      'Karadeniz müziğiyle ünlü hangi enstrümanlar kullanılır'=>'Karadeniz müziği genellikle kemençe, tulum ve davul gibi enstrümanlarla icra edilir. Bu enstrümanlar, Karadeniz kültürünün önemli bir parçasıdır.',
      'Karadeniz\'de yaşayan insanların genel yaşam tarzı nasıldır'=>'Karadeniz bölgesinde yaşayan insanlar genellikle tarım, hayvancılık ve balıkçılıkla uğraşır. Ayrıca bölgede halk müziği ve folklor etkinlikleri de oldukça yaygındır.',
      'Karadeniz\'de hangi festivaller düzenlenir'=>'Karadeniz bölgesinde düzenlenen festivaller arasında Rize\'de çay festivali, Trabzon\'da fındık festivali gibi etkinlikler yer almaktadır. Bu festivaller bölgenin kültürel ve ekonomik önemini yansıtmaktadır.',
      'Karadeniz bölgesinin en ünlü turistik yerleri hangileridir'=>'Karadeniz bölgesinin en ünlü turistik yerleri arasında Sumela Manastırı, Zilkale, Sümela Manastırı, Ayder Yaylası gibi doğal ve tarihi alanlar bulunmaktadır.',
      'Karadeniz\'deki denizlerin adları nelerdir'=>'Karadeniz bölgesinde yer alan denizler arasında Hazar Denizi, Kara Deniz, Ege Denizi gibi su kütleleri bulunmaktadır. Bu denizler bölgenin ekonomisi ve doğal yaşamı için önemlidir.',
      'Karadenizlilerin en sevdiği yemek hangisidir'=>'Karadenizlilerin en sevdiği yemek mıhlamadır. Mıhlama, tereyağı ve peynirle yapılan nefis bir yemektir.',
      'Karadenizli biri denize giderken ne alır'=>'Karadenizli biri denize giderken tulumunu alır, balık tutmaya gider.',
      'Karadenizli birinin arabasında hangi müzik çalar'=>'Karadenizli birinin arabasında her zaman kemençe müziği çalar. Yol boyunca horon tepmeye hazırlıklı olun!',
      'Karadenizli biri telefonu nasıl kapatır'=>'Karadenizli biri telefonu kapatmak için bataryasını çıkarır. Pratik bir yöntem!',
      'Karadenizli biri yağmur yağdığında ne yapar'=>'Karadenizli biri yağmur yağdığında şemsiyesini değil, direk sırtını alır. Yağmurdan korkmaz!',
      'Karadenizli birinin en sevdiği tatil yeri neresidir'=>'Karadenizli birinin en sevdiği tatil yeri her zaman memleketi Trabzon\'dur. Yeşilin ve doğanın tadını doyasıya çıkarır.',
      'Karadenizli biri kaç yaşına gelince çay içmeye başlar'=>'Karadenizli biri henüz bebekken bile çay içmeye başlar. Çay, onların yaşam tarzının bir parçasıdır!',
      'Karadenizli biri köyüne giderken ne alır'=>'Karadenizli biri köyüne giderken mutlaka yöresel peynir, tereyağı ve mısır ekmeği alır. Hem kendisi yer, hem de köylülerle paylaşır.',
      'Karadenizli birinin en sevdiği kış sporu nedir'=>'Karadenizli birinin en sevdiği kış sporu tabii ki kar üstünde horon tepmek! Karadeniz horonu soğuğa meydan okur!',
      'Karadenizli birinin arabasında neden dört tekerlek değil de üç tekerlek vardır'=>'Karadenizli birinin arabasında dört tekerlek olmaz, üç tekerlek olur; çünkü dördüncü tekerlek çay taşıma sepetidir!',
      'Temel her sabah ne yapar?'=>'Temel her sabah güne "günaydın" diyerek başlar.',
      'Temel\'in en sevdiği yemek nedir?'=>'Temel\'in en sevdiği yemek çorbadır. Çünkü o, çorbayı içe içe büyümüştür.',
      'Temel neden bakkala gitmiş?'=>'Temel bakkala gitmiş, un almış. Nedenini soranlara, "evde un varken, dışarıdan neden alayım" demiş.',
      'Temel hangi takımı tutar?'=>'Temel, futbol takımı olarak ayakkabıcı takımını tutar. Nedenini soranlara, "onlar hep adımı anar" der.',
      'Temel\'in en sevdiği film nedir?'=>'Temel, en sevdiği filmi komedi filmleridir. Çünkü o, her zaman gülmeyi ve neşeyi tercih eder.',
      'Temel ne zaman dondurma yer?'=>'Temel dondurma yaz kış demeden yer. Nedenini soranlara, "dondurma için her zaman bir mevsim vardır" der.',
      'Temel neden gülmüş?'=>'Temel, arkadaşı Dursun bir şakayı anlatınca gülmüş. Nedenini soranlara, "şaka yapmak için" demiş.',
      'Temel neden aynı şakayı defalarca anlatır?'=>'Temel, aynı şakayı defalarca anlatır; çünkü ona göre, her seferinde daha komik olur.',
      'Temel neden kafasına çorap geçirir?'=>'Temel kafasına çorap geçirir; çünkü rüzgarın kafasını üşüttüğünü düşünür.',
      'Temel neden tiyatro izler?'=>'Temel, tiyatro izler çünkü orada sahnede kimse onun sesini duyamaz ve o da rahatça bağırabilir.',
      'Güneş Sistemi\'nin en büyük gezegeni hangisidir?'=>'Güneş Sistemi\'nin en büyük gezegeni Jüpiter\'dir.',
      'Güneş Sistemi\'nin en küçük gezegeni hangisidir?'=>'Güneş Sistemi\'nin en küçük gezegeni Merkür\'dür.',
      'Güneş Sistemi\'nde yer alan en büyük cüce gezegen hangisidir?'=>'Güneş Sistemi\'nde yer alan en büyük cüce gezegen Plüton\'dur.',
      'Güneş Sistemi\'nin en sıcak gezegeni hangisidir?'=>'Güneş Sistemi\'nin en sıcak gezegeni Venüs\'tür.',
      'Güneş Sistemi\'nin en soğuk gezegeni hangisidir?'=>'Güneş Sistemi\'nin en soğuk gezegeni Neptün\'dür.',
      'Güneş Sistemi\'nde yer alan tek yerleşilebilir gezegen hangisidir?'=>'Güneş Sistemi\'nde yer alan tek yerleşilebilir gezegen Dünya\'dır.',
      'Güneş Sistemi\'nde kaç tane cüce gezegen vardır?'=>'Güneş Sistemi\'nde şu anda kabul edilen 5 cüce gezegen bulunmaktadır.',
      'Ay, hangi gezegenin uydusudur?'=>'Ay, Dünya\'nın uydusudur.',
      'Güneş Sistemi\'nde en uzun gün hangi gezegende yaşanır?'=>'Güneş Sistemi\'nde en uzun gün Jüpiter\'de yaşanır.',
      'Güneş Sistemi\'nde yer alan en büyük asteroid hangisidir?'=>'Güneş Sistemi\'nde yer alan en büyük asteroid Ceres\'dir.',
      'Güneş Sistemi\'ndeki en büyük uydu hangi gezegenin uydusudur?'=>'Güneş Sistemi\'ndeki en büyük uydu Jüpiter\'in uydusu Ganymede\'dir.',
      'Güneş Sistemi\'ndeki en büyük gezegen halkası hangi gezegenin halkasıdır?'=>'Güneş Sistemi\'ndeki en büyük gezegen halkası Satürn\'ün halkasıdır.',
      'Güneş Sistemi\'nin dış gezegenlerine ne ad verilir?'=>'Güneş Sistemi\'nin dış gezegenlerine Jovian gezegenler de denir.',
      'Güneş Sistemi\'nin iç gezegenlerine ne ad verilir?'=>'Güneş Sistemi\'nin iç gezegenlerine Terrestrial gezegenler de denir.',
      'Güneş Sistemi\'nde yer alan en büyük volkan hangi gezegendeki?'=>'Güneş Sistemi\'nde yer alan en büyük volkan Mars\'taki Olympus Mons\'tur.',
      'Güneş Sistemi\'ndeki en büyük gök cismi hangisidir?'=>'Güneş Sistemi\'ndeki en büyük gök cismi Satürn\'deki Titan uydusudur.',
      'Güneş Sistemi\'ndeki en uzun nehir hangi gezegende bulunur?'=>'Güneş Sistemi\'ndeki en uzun nehir Venüs\'te bulunur.',
      'Güneş Sistemi\'nde yer alan en büyük çatlağın bulunduğu uydunun adı nedir?'=>'Güneş Sistemi\'nde yer alan en büyük çatlağın bulunduğu uydunun adı Europav\'dır.',
      'Güneş Sistemi\'nde yaşamın en olası olduğu gezegen hangisidir?'=>'Güneş Sistemi\'nde yaşamın en olası olduğu gezegen Mars\'tır.',
      'Güneş Sistemi\'nde yer alan en büyük göl hangi gezegende bulunur?'=>'Güneş Sistemi\'nde yer alan en büyük göl Titan\'da bulunur.',
      'Güneş Sistemi\'nde kaç tane doğal uydu bulunmaktadır?'=>'Güneş Sistemi\'nde toplamda 214 tanesi bilinen olmak üzere 200\'den fazla doğal uydu bulunmaktadır.',
      'Güneş Sistemi\'ndeki en soğuk gezegen atmosferine sahiptir?'=>'Güneş Sistemi\'ndeki en soğuk gezegen atmosferine sahip olan gezegen Neptün\'dür.',
      'Güneş Sistemi\'nde yaşamın kesinlikle mümkün olmadığı gezegen hangisidir?'=>'Güneş Sistemi\'nde yaşamın kesinlikle mümkün olmadığı gezegen Venüs\'tür.',
      'Güneş Sistemi\'nde kaç tane cüce gezegen vardır?'=>'Güneş Sistemi\'nde kabul edilen 5 cüce gezegen bulunmaktadır.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegene çarptı?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Satürn\'ün uydusu olan Titan\'a çarptı.',
      'Güneş Sistemi\'ndeki en büyük çukur hangi gezegenin uydusunda bulunur?'=>'Güneş Sistemi\'ndeki en büyük çukur, Mars\'ın uydusu olan Phobos\'ta bulunur.',
      'Güneş Sistemi\'ndeki en büyük dağ hangi gezegende yer almaktadır?'=>'Güneş Sistemi\'ndeki en büyük dağ, Mars\'taki Olympus Mons\'tur.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegene düştü?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Jüpiter\'e düştü.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük takım yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük takım yıldız, Ursa Major\'dur.',
      'Güneş Sistemi\'nde kaç tane bilinen meteor yağmuru vardır?'=>'Güneş Sistemi\'nde 12 tanesi bilinen meteor yağmuru bulunmaktadır.',
      'Güneş Sistemi\'ndeki en büyük buzulu bulunduran gezegen hangisidir?'=>'Güneş Sistemi\'ndeki en büyük buzulu bulunduran gezegen Neptün\'dür.',
      'Güneş Sistemi\'ndeki en büyük teleskop hangi gezegende yer almaktadır?'=>'Güneş Sistemi\'ndeki en büyük teleskop Dünya\'da yer almaktadır.',
      'Güneş Sistemi\'nde en fazla gezegen hangi takım yıldızında bulunur?'=>'Güneş Sistemi\'nde en fazla gezegen, Andromeda Takım Yıldızı\'nda bulunur.',
      'Güneş Sistemi\'nde yaşamın en olası olduğu gezegenin adı nedir?'=>'Güneş Sistemi\'nde yaşamın en olası olduğu gezegen Mars\'tır.',
      'Güneş Sistemi\'ndeki en büyük çarpışma hangi gezegenin yüzeyinde gerçekleşti?'=>'Güneş Sistemi\'ndeki en büyük çarpışma, Jüpiter\'in yüzeyinde gerçekleşti.',
      'Güneş Sistemi\'nde yer alan en büyük uydu hangi gezegenin uydusudur?'=>'Güneş Sistemi\'ndeki en büyük uydu Jüpiter\'in uydusu Ganymede\'dir.',
      'Güneş Sistemi\'nde yer alan en büyük asteroid hangisidir?'=>'Güneş Sistemi\'ndeki en büyük asteroid Ceres\'dir.',
      'Güneş Sistemi\'nde yer alan en büyük uydu hangi gezegenin uydusudur?'=>'Güneş Sistemi\'ndeki en büyük uydu Jüpiter\'in uydusu Ganymede\'dir.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük teleskop hangi gezegende bulunur?'=>'Güneş Sistemi\'ndeki en büyük teleskop Dünya\'da bulunur.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegene düşmüştür?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Jüpiter\'e düşmüştür.',
      'Güneş Sistemi\'ndeki en büyük gök cismi hangi gezegende yer almaktadır?'=>'Güneş Sistemi\'ndeki en büyük gök cismi Satürn\'ün uydusu Titan\'dır.',
      'Güneş Sistemi\'nde en uzun gün hangi gezegenin yüzeyinde yaşanır?'=>'Güneş Sistemi\'nde en uzun gün Jüpiter\'in yüzeyinde yaşanır.',
      'Güneş Sistemi\'ndeki en büyük buzulu bulunduran gezegen hangisidir?'=>'Güneş Sistemi\'ndeki en büyük buzulu bulunduran gezegen Neptün\'dür.',
      'Güneş Sistemi\'ndeki en büyük volkan hangi gezegenin yüzeyinde yer almaktadır?'=>'Güneş Sistemi\'ndeki en büyük volkan Mars\'ın yüzeyinde yer almaktadır.',
      'Güneş Sistemi\'ndeki en büyük dağ hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük dağ Mars\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı nedir?'=>'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı Europa\'dır.',
      'Güneş Sistemi\'ndeki en büyük göl hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük göl Titan\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük nehir hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük nehir Venüs\'ün yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük takım yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük takım yıldız Andromeda Takım Yıldızı\'dır.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegenin yüzeyine düşmüştür?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Jüpiter\'in yüzeyine düşmüştür.',
      'Güneş Sistemi\'ndeki en büyük volkan hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük volkan Mars\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük gök cismi hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük gök cismi Satürn\'ün uydusu Titan\'dır.',
      'Güneş Sistemi\'ndeki en büyük dağ hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük dağ Mars\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı nedir?'=>'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı Europa\'dır.',
      'Güneş Sistemi\'ndeki en büyük göl hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük göl Titan\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük nehir hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük nehir Venüs\'ün yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük takım yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük takım yıldız Andromeda Takım Yıldızı\'dır.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegenin yüzeyine düşmüştür?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Jüpiter\'in yüzeyine düşmüştür.',
      'Güneş Sistemi\'ndeki en büyük volkan hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük volkan Mars\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük gök cismi hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük gök cismi Satürn\'ün uydusu Titan\'dır.',
      'Güneş Sistemi\'ndeki en büyük dağ hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük dağ Mars\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı nedir?'=>'Güneş Sistemi\'ndeki en büyük çatlağın bulunduğu uydunun adı Europa\'dır.',
      'Güneş Sistemi\'ndeki en büyük göl hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük göl Titan\'ın yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük nehir hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük nehir Venüs\'ün yüzeyinde bulunur.',
      'Güneş Sistemi\'ndeki en büyük takım yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük takım yıldız Andromeda Takım Yıldızı\'dır.',
      'Güneş Sistemi\'ndeki en büyük yıldız hangisidir?'=>'Güneş Sistemi\'ndeki en büyük yıldız Güneş\'tir.',
      'Güneş Sistemi\'ndeki en büyük göktaşı hangi gezegenin yüzeyine düşmüştür?'=>'Güneş Sistemi\'ndeki en büyük göktaşı Jüpiter\'in yüzeyine düşmüştür.',
      'Güneş Sistemi\'ndeki en büyük volkan hangi gezegenin yüzeyinde bulunur?'=>'Güneş Sistemi\'ndeki en büyük volkan Mars\'ın yüzeyinde bulunur.',
      'Koç burcunun sembolü nedir?'=>'Koç burcunun sembolü \Aries\ dir.',
      'Boğa burcunun yönetici gezegeni nedir?'=>'Boğa burcunun yönetici gezegeni \Venüs\ tür.',
      'İkizler burcunun elementi nedir?'=>'İkizler burcunun elementi \Hava\ dır.',
      'Yengeç burcunun olumlu özellikleri nelerdir?'=>'Yengeç burcunun olumlu özellikleri \duyarlılık, sadakat, koruyuculuk ve yaratıcılık\ gibi özelliklerdir.',
      'Aslan burcu insanının en güçlü yanı nedir?'=>'Aslan burcu insanının en güçlü yanı \liderlik özelliği\ dir.',
      'Başak burcu insanı genellikle hangi meslekleri tercih eder?'=>'Başak burcu insanı genellikle \mühendislik, sağlık sektörü, eğitim\ gibi meslekleri tercih eder.',
      'Terazi burcu insanının en büyük zayıf yanı nedir?'=>'Terazi burcu insanının en büyük zayıf yanı \kararsızlık\ olabilir.',
      'Akrep burcu insanı hangi özellikleriyle tanınır?'=>'Akrep burcu insanı \gizemli, tutkulu ve kararlı\ özellikleriyle tanınır.',
      'Yay burcu insanı genellikle hangi tür etkinliklerden hoşlanır?'=>'Yay burcu insanı genellikle \seyahat etmek, macera ve öğrenmeye açık\ tür etkinliklerden hoşlanır.',
      'Oğlak burcunun yönetici gezegeni nedir?'=>'Oğlak burcunun yönetici gezegeni \Satürn\ dür.',
      'Kova burcu insanının en belirgin özelliği nedir?'=>'Kova burcu insanının en belirgin özelliği \bağımsızlık ve yenilikçilik\ tir.',
      'Balık burcu insanının en büyük hayali nedir?'=>'Balık burcu insanının en büyük hayali \dünyayı daha iyi bir yer yapmak ve insanlara yardım etmek\ olabilir.',
      'Koç burcunun sembolü nedir?'=>'Koç burcunun sembolü \Aries\ dir.',
      'Boğa burcunun yönetici gezegeni nedir?'=>'Boğa burcunun yönetici gezegeni \Venüs\ tür.',
      'İkizler burcunun elementi nedir?'=>'İkizler burcunun elementi \Hava\ dır.',
      'Yengeç burcunun olumlu özellikleri nelerdir?'=>'Yengeç burcunun olumlu özellikleri \duyarlılık, sadakat, koruyuculuk ve yaratıcılık\ gibi özelliklerdir.',
      'Aslan burcu insanının en güçlü yanı nedir?'=>'Aslan burcu insanının en güçlü yanı \liderlik özelliği\ dir.',
      'Başak burcu insanı genellikle hangi meslekleri tercih eder?'=>'Başak burcu insanı genellikle \mühendislik, sağlık sektörü, eğitim\ gibi meslekleri tercih eder.',
      'Terazi burcu insanının en büyük zayıf yanı nedir?'=>'Terazi burcu insanının en büyük zayıf yanı \kararsızlık\ olabilir.',
      'Akrep burcu insanı hangi özellikleriyle tanınır?'=>'Akrep burcu insanı \gizemli, tutkulu ve kararlı\ özellikleriyle tanınır.',
      'Yay burcu insanı genellikle hangi tür etkinliklerden hoşlanır?'=>'Yay burcu insanı genellikle \seyahat etmek, macera ve öğrenmeye açık\ tür etkinliklerden hoşlanır.',
      'Oğlak burcunun yönetici gezegeni nedir?'=>'Oğlak burcunun yönetici gezegeni \Satürn\ dür.',
      'Kova burcu insanının en belirgin özelliği nedir?'=>'Kova burcu insanının en belirgin özelliği \bağımsızlık ve yenilikçilik\ tir.',
      'Balık burcu insanının en büyük hayali nedir?'=>'Balık burcu insanının en büyük hayali \dünyayı daha iyi bir yer yapmak ve insanlara yardım etmek\ olabilir.',
      'Astrolojide "ev" terimi neyi ifade eder?'=>'Astrolojide "ev", bir doğum haritasında farklı yaşam alanlarını ve deneyimleri temsil eder.',
      'Astrolojide "yükselen burç" neyi ifade eder?'=>'Astrolojide "yükselen burç", bir doğum haritasında kişinin kişiliğini, dış görünüşünü ve davranışlarını temsil eder.',
      'Astrolojide "Güneş burcu" neyi ifade eder?'=>'Astrolojide "Güneş burcu", bir doğum haritasında kişinin temel özelliklerini ve iç dünyasını temsil eder.',
      'Astrolojide "Ay burcu" neyi ifade eder?'=>'Astrolojide "Ay burcu", bir doğum haritasında kişinin duygusal reaksiyonlarını ve iç dünyasını temsil eder.',
      'Astrolojide "ikiyüzlü" olarak bilinen burç hangisidir?'=>'Astrolojide "ikiyüzlü" olarak bilinen burç, İkizler burcudur.',
      'Astrolojide "büyük uyumlu" olarak bilinen burç hangisidir?'=>'Astrolojide "büyük uyumlu" olarak bilinen burç, Terazi burcudur.',
      'Astrolojide "en ateşli" burç hangisidir?'=>'Astrolojide "en ateşli" burç, Koç burcudur.',
      'Astrolojide "en tutkulu" burç hangisidir?'=>'Astrolojide "en tutkulu" burç, Akrep burcudur.',
      'Astrolojide "en hırslı" burç hangisidir?'=>'Astrolojide "en hırslı" burç, Oğlak burcudur.',
      'Astrolojide "en dengesiz" burç hangisidir?'=>'Astrolojide "en dengesiz" burç, Terazi burcudur.',
      'Astrolojide "en hızlı öfkelenen" burç hangisidir?'=>'Astrolojide "en hızlı öfkelenen" burç, Koç burcudur.',
      'Astrolojide "en sabırlı" burç hangisidir?'=>'Astrolojide "en sabırlı" burç, Boğa burcudur.',
      'Astrolojide "en alıngan" burç hangisidir?'=>'Astrolojide "en alıngan" burç, Yengeç burcudur.',
      'Astrolojide "en soğuk" burç hangisidir?'=>'Astrolojide "en soğuk" burç, Kova burcudur.',
      'Astrolojide "en sıcakkanlı" burç hangisidir?'=>'Astrolojide "en sıcakkanlı" burç, Aslan burcudur.',
      'Astrolojide "en mütevazı" burç hangisidir?'=>'Astrolojide "en mütevazı" burç, Başak burcudur.',
      'Astrolojide "en cömert" burç hangisidir?'=>'Astrolojide "en cömert" burç, Yay burcudur.',
      'Astrolojide "en kıskanç" burç hangisidir?'=>'Astrolojide "en kıskanç" burç, Akrep burcudur.',
      'Astrolojide "en maceraperest" burç hangisidir?'=>'Astrolojide "en maceraperest" burç, Yay burcudur.',
      'Astrolojide "en romantik" burç hangisidir?'=>'Astrolojide "en romantik" burç, Balık burcudur.',
      'Astrolojide "en inatçı" burç hangisidir?'=>'Astrolojide "en inatçı" burç, Boğa burcudur.',
      'Astrolojide "en analitik" burç hangisidir?'=>'Astrolojide "en analitik" burç, Başak burcudur.',
      'Astrolojide "en idealist" burç hangisidir?'=>'Astrolojide "en idealist" burç, Kova burcudur.',
      'Astrolojide "en sadık" burç hangisidir?'=>'Astrolojide "en sadık" burç, Boğa burcudur.',
      'Astrolojide "en zarif" burç hangisidir?'=>'Astrolojide "en zarif" burç, Terazi burcudur.',
      'Astrolojide "en gizemli" burç hangisidir?'=>'Astrolojide "en gizemli" burç, Akrep burcudur.',
      'Astrolojide "en yenilikçi" burç hangisidir?'=>'Astrolojide "en yenilikçi" burç, Kova burcudur.',
      'Astrolojide "en ahenkli" burç hangisidir?'=>'Astrolojide "en ahenkli" burç, Terazi burcudur.',
      'Astrolojide "en karizmatik" burç hangisidir?'=>'Astrolojide "en karizmatik" burç, Aslan burcudur.',
      'Koç burcunun özellikleri nelerdir?'=>'Koç burcu insanları genellikle cesur, liderlik yeteneklerine sahip, hırslı ve enerjik olarak bilinirler.',
      'Boğa burcunun özellikleri nelerdir?'=>'Boğa burcu insanları genellikle sabırlı, kararlı, sadık ve pratik olarak bilinirler.',
      'İkizler burcunun özellikleri nelerdir?'=>'İkizler burcu insanları genellikle meraklı, esnek, konuşkan ve sosyal olarak bilinirler.',
      'Yengeç burcunun özellikleri nelerdir?'=>'Yengeç burcu insanları genellikle duygusal, koruyucu, hassas ve evcil olarak bilinirler.',
      'Aslan burcunun özellikleri nelerdir?'=>'Aslan burcu insanları genellikle cömert, karizmatik, yaratıcı ve kendine güvenen olarak bilinirler.',
      'Başak burcunun özellikleri nelerdir?'=>'Başak burcu insanları genellikle detaycı, düzenli, analitik ve titiz olarak bilinirler.',
      'Terazi burcunun özellikleri nelerdir?'=>'Terazi burcu insanları genellikle uyumlu, adil, zarif ve sosyal olarak bilinirler.',
      'Akrep burcunun özellikleri nelerdir?'=>'Akrep burcu insanları genellikle tutkulu, gizemli, kararlı ve güçlü olarak bilinirler.',
      'Yay burcunun özellikleri nelerdir?'=>'Yay burcu insanları genellikle maceraperest, iyimser, özgür ruhlu ve cesur olarak bilinirler.',
      'Oğlak burcunun özellikleri nelerdir?'=>'Oğlak burcu insanları genellikle disiplinli, hırslı, sabırlı ve kararlı olarak bilinirler.',
      'Kova burcunun özellikleri nelerdir?'=>'Kova burcu insanları genellikle yenilikçi, bağımsız, özgün ve insancıl olarak bilinirler.',
      'Balık burcunun özellikleri nelerdir?'=>'Balık burcu insanları genellikle duygusal, hayalperest, yardımsever ve iç dünyaları zengin olarak bilinirler.',
      'Türkiye Cumhuriyeti\'nin kurucusu kimdir?'=>'Mustafa Kemal Atatürk Türkiye Cumhuriyeti\'nin kurucusudur.',
      'Hangi tarihte Türkiye Cumhuriyeti kurulmuştur?'=>'Türkiye Cumhuriyeti, 29 Ekim 1923 tarihinde kurulmuştur.',
      'Kurtuluş Savaşı ne zaman başlamıştır?'=>'Kurtuluş Savaşı, 19 Mayıs 1919 tarihinde Mustafa Kemal Atatürk tarafından Samsun\'da başlatılmıştır.',
      'Atatürk\'ün doğum tarihi nedir?'=>'Mustafa Kemal Atatürk, 19 Mayıs 1881 tarihinde doğmuştur.',
      'Lozan Antlaşması ne zaman imzalanmıştır?'=>'Lozan Antlaşması, 24 Temmuz 1923 tarihinde imzalanmıştır.',
      'Türkiye\'de hangi tarihler arasında Tek Parti Dönemi yaşanmıştır?'=>'Tek Parti Dönemi, 1923-1946 yılları arasında Türkiye\'de yaşanmıştır.',
      'Hangi tarihler arasında Türkiye\'de çok partili siyasi hayata geçilmiştir?'=>'Türkiye\'de çok partili siyasi hayata geçiş, 1946 yılında gerçekleşmiştir.',
      'Hangi tarihte Türkiye\'de kadınlar erkeklerle eşit seçme ve seçilme hakkına sahip olmuştur?'=>'Türkiye\'de kadınlar erkeklerle eşit seçme ve seçilme hakkına 1934 yılında sahip olmuşlardır.',
      'İstanbul\'un fethi hangi tarihte gerçekleşmiştir?'=>'İstanbul\'un fethi, 29 Mayıs 1453 tarihinde gerçekleşmiştir.',
      'Anadolu Selçuklu Devleti hangi tarihler arasında varlığını sürdürmüştür?'=>'Anadolu Selçuklu Devleti, 1077-1308 yılları arasında varlığını sürdürmüştür.',
      'Osmanlı Devleti hangi tarihler arasında varlık göstermiştir?'=>'Osmanlı Devleti, 1299-1922 yılları arasında varlık göstermiştir.',
      'Cumhuriyet Dönemi hangi tarihler arasında yaşanmıştır?'=>'Cumhuriyet Dönemi, 1923 yılından günümüze kadar olan süreci ifade etmektedir.',
      'Türk İslam Devletleri hangi dönemlerde kurulmuştur?'=>'Türk İslam Devletleri, 11. yüzyıldan itibaren Orta Asya\'da kurulmaya başlamıştır.',
      'Türkler hangi dönemde Anadolu\'ya yerleşmişlerdir?'=>'Türkler, 11. yüzyıldan itibaren Anadolu\'ya yerleşmeye başlamışlardır.',
      'Osmanlı İmparatorluğu hangi dönemde çöküşe geçmiştir?'=>'Osmanlı İmparatorluğu, 19. yüzyılın sonlarından itibaren çöküşe geçmiştir.',
      'Kurtuluş Savaşı\'nda hangi cephe önemli bir rol oynamıştır?'=>'Kurtuluş Savaşı\'nda Büyük Taarruz ve Sakarya Meydan Muharebesi önemli bir rol oynamıştır.',
      'Cumhuriyet Dönemi\'nde hangi reformlar gerçekleştirilmiştir?'=>'Cumhuriyet Dönemi\'nde çok sayıda reform gerçekleştirilmiş olup bunlar arasında eğitim, hukuk, dil ve kadın hakları reformları bulunmaktadır.',
      'Atatürk\'ün ölüm tarihi nedir?'=>'Mustafa Kemal Atatürk, 10 Kasım 1938 tarihinde vefat etmiştir.',
      'Türkiye\'de ilk siyasi partinin kuruluş tarihi nedir?'=>'Türkiye\'de ilk siyasi parti olan Osmanlı Mebuslar Cemiyeti, 1908 yılında kurulmuştur.',
      'Türkiye Cumhuriyeti Anayasası ne zaman kabul edilmiştir?'=>'Türkiye Cumhuriyeti Anayasası, 1924 yılında kabul edilmiştir.',
      'Atatürk\'ün kabrine yapılan ziyaretler hangi tarihte başlamıştır?'=>'Atatürk\'ün kabrine yapılan ziyaretler, 10 Kasım 1938 tarihinde başlamıştır.',
      'Türkiye Cumhuriyeti\'nde ilk kadın milletvekili kimdir?'=>'Türkiye Cumhuriyeti\'nde ilk kadın milletvekili, 1935 yılında seçilen Mebrure Gönenç\'tir.',
      'Hangi tarihler arasında Türkiye\'de Demokrat Parti iktidarda olmuştur?'=>'Demokrat Parti iktidarı, 1950-1960 yılları arasında Türkiye\'de olmuştur.',
      'Türkiye\'de ilk demokratik seçimler hangi tarihlerde yapılmıştır?'=>'Türkiye\'de ilk demokratik seçimler, 1950 yılında yapılmıştır.',
      'Türkiye Cumhuriyeti\'nde ilk başbakan kimdir?'=>'Türkiye Cumhuriyeti\'nde ilk başbakan, Mustafa Kemal Atatürk\'ün ardından İsmet İnönü\'dür.',
      'Cumhuriyet Dönemi\'nde yapılan alfabe değişikliği hangi tarihlerde gerçekleşmiştir?'=>'Cumhuriyet Dönemi\'nde yapılan alfabe değişikliği, 1928 yılında gerçekleşmiştir.',
      'Türkiye Cumhuriyeti\'nde ilk yerli otomobil hangi marka ve modeldir?'=>'Türkiye Cumhuriyeti\'nde ilk yerli otomobil, Devrim adıyla üretilmiş olup 1961 yılında tanıtılmıştır.',
      'Türk Kurtuluş Savaşı\'nda hangi savaşlar önemli rol oynamıştır?'=>'Türk Kurtuluş Savaşı\'nda önemli savaşlar arasında Sakarya Meydan Muharebesi, Büyük Taarruz ve Dumlupınar Meydan Muharebesi bulunmaktadır.',
      'Türklerin İslamiyeti kabul etmeleri hangi dönemde gerçekleşmiştir?'=>'Türklerin İslamiyeti kabul etmeleri, 8. ve 9. yüzyıllar arasında gerçekleşmiştir.',
      'Osmanlı İmparatorluğu\'nun kuruluş tarihi nedir?'=>'Osmanlı İmparatorluğu, 1299 yılında Osman Bey tarafından kurulmuştur.',
      'Türkiye Cumhuriyeti\'nde ilk üniversite hangi tarihlerde kurulmuştur?'=>'Türkiye Cumhuriyeti\'nde ilk üniversite, 20 Nisan 1923 tarihinde Ankara Üniversitesi olarak kurulmuştur.',
      'Türklerin Orta Asya\'dan Anadolu\'ya göç etmeleri hangi dönemde gerçekleşmiştir?'=>'Türklerin Orta Asya\'dan Anadolu\'ya göç etmeleri, 11. yüzyıldan itibaren gerçekleşmiştir.',
      'Osmanlı Devleti\'nin yıkılışı hangi tarihlerde gerçekleşmiştir?'=>'Osmanlı Devleti\'nin yıkılışı, 1922-1923 yılları arasında gerçekleşmiştir.',
      'Osmanlı İmparatorluğu\'nda hangi dönemde Tanzimat Fermanı ilan edilmiştir?'=>'Osmanlı İmparatorluğu\'nda Tanzimat Fermanı, 3 Kasım 1839 tarihinde ilan edilmiştir.',
      'Osmanlı İmparatorluğu\'nda hangi dönemde Islahat Fermanı ilan edilmiştir?'=>'Osmanlı İmparatorluğu\'nda Islahat Fermanı, 18 Şubat 1856 tarihinde ilan edilmiştir.',
      'Osmanlı Devleti\'nde hangi dönemde Babıali\'deki ilk gazete yayımlanmıştır?'=>'Osmanlı Devleti\'nde ilk gazete, 1831 yılında Takvim-i Vekayi adıyla yayımlanmıştır.',
      'Osmanlı İmparatorluğu\'nda hangi dönemde İlk Anayasa kabul edilmiştir?'=>'Osmanlı İmparatorluğu\'nda ilk anayasa, 1876 yılında kabul edilmiştir.',
      'Osmanlı Devleti\'nde hangi dönemde Divan-ı Hümayun kaldırılmıştır?'=>'Osmanlı Devleti\'nde Divan-ı Hümayun, 1839 yılında kaldırılmıştır.',
      'Osmanlı Devleti\'nde hangi dönemde Harbiye Nezareti kurulmuştur?'=>'Osmanlı Devleti\'nde Harbiye Nezareti, 1843 yılında kurulmuştur.',
      'Göktürkler hangi dönemde yaşamıştır?'=>'Göktürkler Orta Asya\'da 6. ve 8. yüzyıllar arasında yaşamıştır.',
      'Hunlar hangi dönemde büyük bir imparatorluk kurmuştur?'=>'Hunlar M.Ö. 4. ve 6. yüzyıllar arasında büyük bir imparatorluk kurmuştur.',
      'Asya Türkleri nerede yaşamıştır?'=>'Asya Türkleri Orta Asya\'da yaşamıştır.',
      'Türk halklarının ortak özellikleri nelerdir?'=>'Türk halklarının ortak özellikleri arasında göçebe yaşam tarzı, ata kültü ve savaşçı ruh gibi unsurlar bulunur.',
      'Göktürk Kağanlığı hangi dönemde kurulmuştur?'=>'Göktürk Kağanlığı M.S. 6. yüzyılda kurulmuştur ve Orta Asya\'da büyük bir etki alanı oluşturmuştur.',
      'Hun İmparatorluğu\'nun başkenti neresidir?'=>'Hun İmparatorluğu\'nun başkenti genellikle Karadeniz kıyılarındaki Panticapaeum olarak bilinir.',
      'Orta Asya\'da kurulan ilk Türk devleti hangisidir?'=>'Orta Asya\'da kurulan ilk Türk devleti Göktürklerdir.',
      'Türk tarihinin önemli dönemeçleri nelerdir?'=>'Türk tarihinin önemli dönemeçleri arasında Orhun Abideleri\'nin yazılması, Malazgirt Meydan Muharebesi ve Türk Kurtuluş Savaşı gibi olaylar bulunur.',
      'Hun İmparatorluğu\'nun yıkılmasında hangi faktörler etkili olmuştur?'=>'Hun İmparatorluğu\'nun yıkılmasında iç çekişmeler, dış baskılar ve iklim değişiklikleri gibi faktörler etkili olmuştur.',
      'Türk tarihinin önemli liderleri kimlerdir?'=>'Türk tarihinin önemli liderleri arasında Gazi Mustafa Kemal Atatürk, Osman Gazi, Alp Arslan ve Cengiz Han gibi isimler bulunur.',
      'Göktürk alfabesi hangi yazı sistemine dayanır?'=>'Göktürk alfabesi, Orhun ve Yenisey yazıtları ile bilinen bir Türk alfabesidir ve Eski Türkçe yazı dilini temsil eder.',
      'Orta Asya\'da yaşamış olan diğer Türk boyları nelerdir?'=>'Orta Asya\'da yaşamış olan diğer Türk boyları arasında Uygurlar, Karahanlılar, Karluklar ve Hazarlar gibi boylar bulunur.',
      'Türk devletlerinin ilk hangisi kurulmuştur?'=>'Göktürk Devleti, Orhun Yazıtları\'na göre 552 yılında kurulmuştur.',
      'Osmanlı İmparatorluğu hangi yüzyılda kurulmuştur?'=>'Osmanlı İmparatorluğu, 1299 yılında Osman Bey tarafından kurulmuştur.',
      'Türkiye Cumhuriyeti ne zaman kurulmuştur?'=>'Türkiye Cumhuriyeti, 29 Ekim 1923 tarihinde ilan edilmiştir.',
      'Selçuklu Devleti hangi dönemde hüküm sürmüştür?'=>'Selçuklu Devleti, 11. yüzyıldan 13. yüzyılın ortalarına kadar Orta Doğu ve Anadolu\'da hüküm sürmüştür.',
      'Anadolu Selçuklu Devleti hangi şehirde kurulmuştur?'=>'Anadolu Selçuklu Devleti, 1077 yılında İznik\'te kurulmuştur.',
      'Kara Koyunlu Devleti ve Ak Koyunlu Devleti hangi dönemde var olmuştur?'=>'Kara Koyunlu ve Ak Koyunlu devletleri, 14. yüzyılın sonlarından 16. yüzyılın ortalarına kadar hüküm sürmüşlerdir.',
      'Oğuz Kağan Destanı hangi dönemde yazılmıştır?'=>'Oğuz Kağan Destanı, 10. yüzyılda yazılmıştır ve Türk halkının kökeni ve kültürel tarihi hakkında bilgi vermektedir.',
      'Anadolu\'nun fethi hangi dönemde gerçekleşmiştir?'=>'Anadolu\'nun fethi, 11. yüzyılın sonlarından itibaren Selçuklu Türkleri tarafından gerçekleştirilmiştir.',
      'Kutadgu Bilig eseri kimin tarafından yazılmıştır?'=>'Kutadgu Bilig, Kaşgarlı Mahmut tarafından 11. yüzyılda yazılmıştır ve Türk edebiyatının önemli eserlerinden biridir.',
      'Anadolu\'da kurulan ilk Türk beyliği hangisidir?'=>'Anadolu\'da kurulan ilk Türk beyliği, 1077 yılında Söğüt\'te kurulan Osmanlı Beyliği\'dir.',
      'Büyük Selçuklu Devleti hangi dönemde var olmuştur?'=>'Büyük Selçuklu Devleti, 11. yüzyıldan 13. yüzyıla kadar Orta Doğu ve Anadolu\'da var olmuştur.',
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

    if ($best_similarity < 50) {
         // Yapay zekadan cevap al
         $ai_response = getAIResponse($text);

         // Cevabı işle
         $ai_response_decoded = json_decode($ai_response, true);
         $response = $ai_response_decoded['candidates'][0]['content']['parts'][0]['text'];

         // Eğer yapay zekadan bir cevap alırsak
         if ($response) {
             // Kullanıcıya cevabı gönder
             $telegram->sendMessage([
                 'chat_id' => $chatId,
                 'text' => $response
             ]);
         } else {
             // Yapay zekadan cevap alınamazsa
             $response = "Üzgünüm, bu konuda bir bilgim yok. { $user_message }";
             $telegram->sendMessage([
                 'chat_id' => $chatId,
                 'text' => $response
             ]);
         }
     }
 }
?>
