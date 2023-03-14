<?php
defined('_MRKEN_CMS') or die('ERROR!');

class RobotsDetect
{
    private $robot_name = FALSE;
    private $robot_type = FALSE;
    private $user_agent;

    public function __construct($user_agent){
        $this->user_agent = $user_agent;
        
        if (preg_match('/google/i', $user_agent) || preg_match('/gsa-crawler/i', $user_agent)) {
            $this->google_bots();
        
        }elseif(preg_match('/yandex/i', $user_agent)){
            $this->yandex_bots();
        
        }elseif(preg_match('/yahoo/i', $user_agent)){
            $this->yahoo_bots();
            
        }elseif(preg_match('/dotbot/i', $user_agent)){
            $this->dot_bots();
            
         }elseif(preg_match('/aport/i', $user_agent)){
            $this->aport_bots();
            
         }elseif(preg_match('/nigma/i', $user_agent)){
            $this->nigma_bots();
            
         }elseif(preg_match('/mail.ru/i', $user_agent)){
            $this->mail_bots();
            
         }elseif(preg_match('/msn/i', $user_agent) || preg_match('/librabot/i', $user_agent) || preg_match('/llssbot/i', $user_agent)
         || preg_match('/bing/i', $user_agent) || preg_match('/danger hiptop/i', $user_agent) || preg_match('/msr/i', $user_agent)
         || preg_match('/vancouver/i', $user_agent)){
            $this->bing_bots();
         }elseif(preg_match('/ask jeeves/i', $user_agent)){
            $this->ask_bots();
            
         }elseif(preg_match('/archive/i', $user_agent)){
            $this->iarchive_bots();
            
         }elseif(preg_match('/gigabot/i', $user_agent)){
            $this->giga_bots();
            
         }elseif(preg_match('/setlinks/i', $user_agent)){
            $this->setlinks_bots();
            
         }elseif(preg_match('/mlbot/i', $user_agent)){
            $this->mlbot_bots();
            
         }elseif(preg_match('/http:\/\//i', $user_agent)){
            $this->robot_name = 'Other';
            $this->robot_type = 'Other';
            
         }
        
        
        }
    
    private function yandex_bots(){
    // Проверка яндекс ботов //
    $this->robot_name = 'Yandex';
        if(preg_match('/Mozilla\/5.0 \(compatible; YandexBot\/(.*); MirrorDetector; (.*)\)/', $this->user_agent)){
            $this->robot_type = 'Yandex MirrorDetector';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexBot\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexBot';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexAddurl\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexAddurl';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexBlogs\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexBlogs';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexCatalog\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexCatalog';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexDirect\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexDirect';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexFavicons\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexFavicons';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexImageResizer\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexImageResizer';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexImages\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexImages';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexMedia\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexMedia';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexMetrika\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexMetrika';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexNews\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexNews';
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; YandexVideo\/(.*)\)(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexVideo';
        }elseif(preg_match('/Yandex\/(.*) \(compatible; (.*); (.*)\)/', $this->user_agent)){
            $this->robot_type = 'YandexBot';
        }elseif(preg_match('/YandexSomething\/(.*)/', $this->user_agent)){
            $this->robot_type = 'YandexSomething';
        }else{
            $this->robot_name = FALSE;
        }
    // Конец яндексопроверки
    }
    
    private function google_bots(){
    // Проверка гугл ботов //
    $this->robot_name = 'Google';    
        if(preg_match('/(.*)\(compatible; Googlebot-Mobile\/2.(.*); (.*)http:\/\/www.google.com\/bot.html\)/', $this->user_agent)){
            $this->robot_type = 'Googlebot-Mobile';
        
        }elseif(preg_match('/(.*)Google Wireless Transcoder(.*)/', $this->user_agent)){
            $this->robot_type = 'Google Wireless Transcoder';
        
        }elseif(preg_match('/AdsBot-Google \((.*)http:\/\/www.google.com\/adsbot.html\)/', $this->user_agent)){
            $this->robot_type = 'AdsBot-Google';
        
        }elseif(preg_match('/AdsBot-Google-Mobile \((.*)http:\/\/www.google.com\/mobile\/adsbot.html\) Mozilla \(iPhone; U; CPU iPhone OS 3 0 like Mac OS X\) AppleWebKit \(KHTML, like Gecko\) Mobile Safari/', $this->user_agent)){
            $this->robot_type = 'AdsBot-Google-Mobile';
        
        }elseif(preg_match('/AppEngine-Google(.*)/', $this->user_agent)){
            $this->robot_type = 'AppEngine-Google';
        
        }elseif(preg_match('/Feedfetcher-Google-iGoogleGadgets;(.*)/', $this->user_agent)){
            $this->robot_type = 'iGoogleGadgets';
        
        }elseif(preg_match('/Feedfetcher-Google;(.*)/', $this->user_agent)){
            $this->robot_type = 'Feedfetcher-Google';
        
        }elseif(preg_match('/Google OpenSocial agent \(http:\/\/www.google.com\/feedfetcher.html\)/', $this->user_agent)){
            $this->robot_type = 'Google OpenSocial';
        
        }elseif(preg_match('/Google-Site-Verification\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Google-Site-Verification';
        
        }elseif(preg_match('/Google-Sitemaps\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Google-Sitemaps';
        
        }elseif(preg_match('/Googlebot-Image\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Googlebot-Image';
        
        }elseif(preg_match('/Googlebot-News\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Googlebot-News';
        
        }elseif(preg_match('/googlebot-urlconsole/', $this->user_agent)){
            $this->robot_type = 'googlebot-urlconsole';
        
        }elseif(preg_match('/Googlebot-Video\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Google-Video';
        
        }elseif(preg_match('/Googlebot\/2.1 \((.*)http:\/\/www.google.com\/bot.html\)/', $this->user_agent)){
            $this->robot_type = 'Googlebot';
        
        }elseif(preg_match('/Googlebot\/2.1 \((.*)http:\/\/www.googlebot.com\/bot.html\)/', $this->user_agent)){
            $this->robot_type = 'Googlebot';
        
        }elseif(preg_match('/Googlebot\/Test(.*)/', $this->user_agent)){
            $this->robot_type = 'Googlebot/Test';
        
        }elseif(preg_match('/Mediapartners-Google(.*)/', $this->user_agent)){
            $this->robot_type = 'Mediapartners-Google';
        
        }elseif(preg_match('/GoogleFriendConnect\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Google Friend Connect';
        
        }elseif(preg_match('/Mozilla\/(.*).0 \(compatible; Google Desktop(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Google Desktop';
            
        }elseif(preg_match('/gsa-crawler(.*)/', $this->user_agent)){
            $this->robot_type = 'Google Search Appliance';
            
        }elseif(preg_match('/Mozilla\/5.0 (.*) AppleWebKit\/525.13 \(KHTML, like Gecko; Google Web Preview\) Version\/3.1 Safari\/525.13/', $this->user_agent)){
            $this->robot_type = 'Google Web Preview';
            
        }elseif(preg_match('/Mozilla\/5.0 \(compatible\) Feedfetcher-Google; \( http:\/\/www.google.com\/feedfetcher.html\)/', $this->user_agent)){
            $this->robot_type = 'Google Feedfetcher';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Google Keyword Tool;(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Google Keyword Tool';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Googlebot\/2.1; (.*)http:\/\/www.google.com\/bot.html\)/', $this->user_agent)){
            $this->robot_type = 'Googlebot';
        }else{
            $this->robot_name = FALSE;
        }
    // Конец гуглопроверки
    }
    
    private function bing_bots(){
    // Проверка бинг ботов //
    $this->robot_name = 'Bing';    
        if(preg_match('/adidxbot\/1.1 \((.*)http:\/\/search.msn.com\/msnbot.htm\)/', $this->user_agent)){
            $this->robot_type = 'adidxbot';
        
        }elseif(preg_match('/librabot\/1.0 \((.*)\)/', $this->user_agent)){
            $this->robot_type = 'librabot';
        
        }elseif(preg_match('/llssbot\/1.0/', $this->user_agent)){
            $this->robot_type = 'llssbot';
        
        }elseif(preg_match('/Microsoft Bing Mobile SocialStreams Bot/', $this->user_agent)){
            $this->robot_type = 'Microsoft Bing Mobile SocialStreams Bot';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; bingbot\/2.(.*)http:\/\/www.bing.com\/bingbot.htm\)/', $this->user_agent)){
            $this->robot_type = 'BingBot';
        
        }elseif(preg_match('/Mozilla\/5.0 \(Danger hiptop 3.(.*); U; rv:1.7.(.*)\) Gecko\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Danger';
        
        }elseif(preg_match('/MSMOBOT\/1.1(.*)/', $this->user_agent)){
            $this->robot_type = 'msnbot-mobile';
        
        }elseif(preg_match('/MSNBot-Academic\/1.0(.*)/', $this->user_agent)){
            $this->robot_type = 'MSNBot-Academic';
        
        }elseif(preg_match('/msnbot-media\/1.(.*)/', $this->user_agent)){
            $this->robot_type = 'msnbot-media';
    
        }elseif(preg_match('/MSNBot-News\/1.(.*)/', $this->user_agent)){
            $this->robot_type = 'MSNBot-News';
        
        }elseif(preg_match('/MSNBot-NewsBlogs\/1.(.*)/', $this->user_agent)){
            $this->robot_type = 'MSNBot-NewsBlogs';
        
        }elseif(preg_match('/msnbot-products/', $this->user_agent)){
            $this->robot_type = 'msnbot-products';
        
        }elseif(preg_match('/msnbot-webmaster\/1.0 \((.*)http:\/\/search.msn.com\/msnbot.htm\)/', $this->user_agent)){
            $this->robot_type = 'msnbot-webmaster tools';
        
        }elseif(preg_match('/msnbot\/(.*)/', $this->user_agent)){
            $this->robot_type = 'msnbot';
        
        }elseif(preg_match('/MSR-ISRCCrawler/', $this->user_agent)){
            $this->robot_type = 'MSR-ISRCCrawler';
        
        }elseif(preg_match('/MSRBOT(.*)/', $this->user_agent)){
            $this->robot_type = 'MSRBOT';
        
        }elseif(preg_match('/renlifangbot\/1.0 \((.*)http:\/\/search.msn.com\/msnbot.htm\)/', $this->user_agent)){
            $this->robot_type = 'renlifangbot';
        
        }elseif(preg_match('/T-Mobile Dash Mozilla\/4.0 \((.*)\) MSNBOT-MOBILE\/1.1 \((.*)\)/', $this->user_agent)){
            $this->robot_type = 'msnbot-mobile';
        
        }elseif(preg_match('/Vancouver(.*)/', $this->user_agent)){
            $this->robot_type = 'Vancouver';
        
        }else{
            $this->robot_name = FALSE;
        }
    // Конец проверки бинг-бота
    
    }
    
    
     private function ask_bots(){
    // Проверка Ask ботов //
    $this->robot_name = 'Ask';    
        if(preg_match('/Mozilla\/(.*).0 \(compatible; Ask Jeeves\/Teoma(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Teoma';
        
        }elseif(preg_match('/Mozilla\/2.0 \(compatible; Ask Jeeves\)/', $this->user_agent)){
            $this->robot_type = 'AskJeeves';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки Ask-бота
    }
    
    private function yahoo_bots(){
    // Проверка yahoo ботов //
    $this->robot_name = 'Yahoo';    
        if(preg_match('/(.*) \(compatible;YahooSeeker\/M1A1-R2D2; (.*)\)/', $this->user_agent)){
            $this->robot_type = 'YahooSeeker-Mobile';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Yahoo! Slurp; http:\/\/help.yahoo.com\/help\/us\/ysearch\/slurp\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo! Slurp';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Yahoo! Slurp\/(.*).0; http:\/\/help.yahoo.com\/help\/us\/ysearch\/slurp\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo! Slurp';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Yahoo! Verifier\/(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo! Verifier';
        ////////////////////////////
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Yahoo!-AdCrawler; http:\/\/help.yahoo.com\/yahoo_adcrawler\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo!-AdCrawler';
        
        }elseif(preg_match('/Mozilla\/5.0 \(Yahoo-MMCrawler\/(.*); mailto:vertical-crawl-support@yahoo-inc.com\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo-MMCrawler';
        
        }elseif(preg_match('/Mozilla\/5.0 \(Yahoo-Test\/(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Yahoo-Test';
        
        }elseif(preg_match('/Yahoo(.*) Mindset/', $this->user_agent)){
            $this->robot_type = 'Yahoo! Mindset';
        
        }elseif(preg_match('/Yahoo Pipes(.*)/', $this->user_agent)){
            $this->robot_type = 'Yahoo Pipes';
        
        }elseif(preg_match('/Yahoo! Slurp\/Site Explorer/', $this->user_agent)){
            $this->robot_type = 'Yahoo! Site Explorer';
        
        }elseif(preg_match('/Yahoo-Blogs\/(.*)/', $this->user_agent)){
            $this->robot_type = 'Yahoo-Blogs';
        
        }elseif(preg_match('/Yahoo-MMAudVid(.*)/', $this->user_agent)){
            $this->robot_type = 'Yahoo-MMAudVid';
        
        }elseif(preg_match('/Yahoo-MMCrawler(.*)/', $this->user_agent)){
            $this->robot_type = 'Yahoo-MMCrawler';
        
        }elseif(preg_match('/YahooExternalCache/', $this->user_agent)){
            $this->robot_type = 'YahooExternalCache';
        
        }elseif(preg_match('/YahooFeedSeeker(.*)/', $this->user_agent)){
            $this->robot_type = 'YahooFeedSeeker';
        
        }elseif(preg_match('/YahooSeeker\/(.*)/', $this->user_agent)){
            $this->robot_type = 'YahooSeeker';
        
        }elseif(preg_match('/YahooVideoSearch(.*)/', $this->user_agent)){
            $this->robot_type = 'YahooVideoSearch';
        
        }elseif(preg_match('/YahooYSMcm(.*)/', $this->user_agent)){
            $this->robot_type = 'YahooYSMcm';
        
        }else{
            $this->robot_name = FALSE;
        
        }
    
    // Конец проверки yahoo-бота
    }
    
    private function dot_bots(){
    // Проверка Dot ботов //
    $this->robot_name = 'DotBot';    
        if(preg_match('/DotBot\/(.*) \(http:\/\/www.dotnetdotcom.org\/(.*)\)/', $this->user_agent)){
            $this->robot_type = 'DotBot';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; DotBot\/(.*); http:\/\/www.dotnetdotcom.org\/(.*)\)/', $this->user_agent)){
            $this->robot_type = 'DotBot';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки Dot-бота
    }
    
    private function giga_bots(){
    // Проверка Giga ботов //
    $this->robot_name = 'Gigabot';    
        if(preg_match('/Gigabot(.*)/', $this->user_agent)){
            $this->robot_type = 'Gigabot';
        
        }elseif(preg_match('/GigabotSiteSearch\/(.*)/', $this->user_agent)){
            $this->robot_type = 'GigabotSiteSearch';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки Giga-бота
    }
    
    
    private function iarchive_bots(){
    // Проверка iarchive ботов //
    $this->robot_name = 'Internet Archive';    
        if(preg_match('/ia_archiver(.*)/', $this->user_agent)){
            $this->robot_type = 'Internet Archive';
        
        }elseif(preg_match('/InternetArchive\/(.*)/', $this->user_agent)){
            $this->robot_type = 'GigabotSiteSearch';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; archive.org_bot(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Internet Archive';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки iarchive-бота
    }
    
    private function aport_bots(){
    // Проверка Aport ботов //
    $this->robot_name = 'Aport';    
        if(preg_match('/Mozilla\/5.0 \(compatible; AportWorm\/(.*); (.*)http:\/\/www.aport.ru\/help\)/', $this->user_agent)){
            $this->robot_type = 'AportWorm';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки Aport-бота
    }
    
    private function mail_bots(){
    // Проверка mail ботов //
    $this->robot_name = 'Mail';    
        if(preg_match('/Mail.Ru(.*)/', $this->user_agent)){
            $this->robot_type = 'Mail.Ru';
        
        }elseif(preg_match('/Mozilla\/(.*) \(compatible; Mail.RU_Bot\/(.*)\)/', $this->user_agent)){
            $this->robot_type = 'Mail.Ru';
        
        }elseif(preg_match('/Mozilla\/5.0 \(compatible; Mail.RU_Bot\/(.*); (.*)http:\/\/go.mail.ru\/help\/robots\)/', $this->user_agent)){
            $this->robot_type = 'Mail.Ru';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки mail-бота
    }
    
    private function nigma_bots(){
    // Проверка nigma ботов //
    $this->robot_name = 'Nigma';    
        if(preg_match('/Mozilla\/5.0 \(compatible; Nigma.ru\/(.*); (.*)\)/', $this->user_agent)){
            $this->robot_type = 'nigma-bot';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки nigma-бота
    }
    
    private function setlinks_bots(){
    // Проверка setlinks ботов //
    $this->robot_name = 'Setlinks';    
        if(preg_match('/SetLinks bot(.*)/', $this->user_agent)){
            $this->robot_type = 'SetLinks bot';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки setlinks-бота
    }
    
    private function mlbot_bots(){
    // Проверка ML ботов //
    $this->robot_name = 'MLBot';    
        if(preg_match('/MLBot (.*)/', $this->user_agent)){
            $this->robot_type = 'MLBot';
        
        }else{
            $this->robot_name = FALSE;
        }
    
    // Конец проверки ML-бота
    }

    
    public function getNameBot(){
        // Выдаём имя бота
        return $this->robot_name;
        }
    
    public function getTypeBot(){
        // Выдаём тип бота
        return $this->robot_type;
        }
    
    
}