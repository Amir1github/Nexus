<?php
	
if (isset($_POST['username'])) {

	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($password != "" || $password != NULL) {

		$p = $pdoConnection->query("SELECT * FROM `usr`")->fetch();
		if (sha_d($p[1], $username) == $password){
			$_SESSION['auth'] = 'true';
		}
	}
}

if ($_SESSION['auth'] !== 'true'){
	include("partials/_login.php");
	die();
}

function sha_d($token,$username){
    $method = 'aes-128-ctr';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    list($token, $iv) = explode("::", $token);
    $key = openssl_digest($username, 'SHA256', TRUE);
    $uname = openssl_decrypt($token, $method, $key, 0, hex2bin($iv));
    unset($token, $method, $key, $iv);
    return $uname;
}
?>