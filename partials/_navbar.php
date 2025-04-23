
<nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
      <div class="nav-top flex-grow-1">
        <div class="container d-flex flex-row h-100 align-items-center">
          <div class="text-center navbar-brand-wrapper d-flex align-items-center">
            <a class="navbar-brand brand-logo" href="index.php"><img src="images/logo.png" alt="logo"/></a>
          </div>
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between flex-grow-1">
            
            <ul class="navbar-nav navbar-nav-right mr-0 ml-auto">
			<li class="nav-item"><label class="switch mt-2">
			<input type="checkbox" onchange="nightMode()" id="nmode"><span class="slider"></span>
			</label>
              </li>
              <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" id="profileDropdown">
                  <img src="https://via.placeholder.com/39x39" alt="profile"/>
                  <span class="nav-profile-name"><?php echo $login;?></span></span>
                </a>
              </li>
            </ul>
            <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="icon-menu text-dark"></span>
            </button>
          </div>
        </div>
      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="index.php" class="nav-link"><span class="fas fa-desktop"></span><span class="menu-title"> Dashboard</span></a>
            </li>
            <li class="nav-item">
              <a href="loader.php" class="nav-link"><i class="fas fa-tasks"></i><span class="menu-title"> Loader</span></a>
            </li>
			<li class="nav-item">
              <a href="search.php" class="nav-link"><i class="fas fa-search"></i><span class="menu-title"> Search</span></a>
            </li>
			<li class="nav-item">
              <a href="settings.php" class="nav-link"><i class="fas fa-cogs"></i><span class="menu-title"> Settings</span></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
