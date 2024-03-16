

# Telegram Php Bot 

Bu Telegram Botu Php ile çalışıp /commands altındaki komutlarla calısmaktadır yeni komut eklemek için commands klasoru altına  handle{komut adı }Command seklınde olusturulan fonksiyonlar sayesinde calısmaktadır 

## Kurulum

1. **Composer Kurulumu**: Öncelikle, projenin bağımlılıklarını yönetmek için [Composer](https://getcomposer.org/) gereklidir. Composer kurulumunu yapılmamışsa [buradan](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) adımları izleyerek kurabilirsiniz.

2. **Bağımlılıkların Yüklenmesi**: Projenizin ana dizininde terminali açın ve aşağıdaki komutu çalıştırarak bağımlılıkları yükleyin:

    ```bash
    composer install
    ```

3. **Bot Token Alınması**: Bir Telegram botu oluşturun ve botun token'ını alın. Token'ı `index.php` dosyasındaki `$botToken` değişkenine atayın.

## Çalıştırma

1. **Web Sunucusu**: Telegram botunu çalıştırmak için bir web sunucusu gereklidir. Önerilen web sunucusu Apache veya Nginx'tir. Bu sunucuları kullanarak `index.php` dosyasını yayınlayabilirsiniz.

2. **Webhook Ayarları**: Telegram botunuzun güncellemeleri alması için bir webhook ayarlamanız gerekebilir. Webhook ayarlarınızı Telegram API belgelerine göre yapılandırın.

3. **Botun Başlatılması**: Web sunucunuzda `index.php` dosyasını çalıştırın. Botunuz artık kullanıma hazırdır.
   
4. Cronjob Kurarak Botunuzu Sürekli Çalıştarabilirsiniz

## Özel Komutlar

- Bu bot, özel komutları işlemek için `commands` dizinindeki PHP dosyalarını kullanır. Komutlarınızı bu dizindeki dosyalara ekleyebilirsiniz.

## Otomatik Yanıtlar

- Bot, kullanıcıların mesajlarına otomatik olarak yanıt verebilir. Otomatik yanıtları `commands/autorespond.php` dosyasında tanımlayabilirsiniz.


