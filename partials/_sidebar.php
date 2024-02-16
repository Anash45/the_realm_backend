<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/img/header-logo.png" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/img/header-logo.png" alt="logo" /></a>
  </div>
  <ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle" src="assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">
              <?php echo $_SESSION['username']; ?>
            </h5>
            <span>
              <?php echo $_SESSION['role']; ?>
            </span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
          <a href="logout.php" class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-logout text-primary"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject ellipsis mb-1 text-small">Logout</p>
            </div>
          </a>
        </div>
      </div>
    </li>
    <?php if (isAdmin()) { ?>
      <li class="nav-item nav-category">
        <span class="nav-link">Accounts Section</span>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link" href="index.php">
          <span class="menu-icon">
            <i class="mdi mdi-record text-success"></i>
          </span>
          <span class="menu-title">Online Users</span>
        </a>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link" href="accounts.php">
          <span class="menu-icon">
            <i class="mdi mdi-account-multiple"></i>
          </span>
          <span class="menu-title">Accounts</span>
        </a>
      </li>
      <li class="nav-item nav-category">
        <span class="nav-link">Store Section</span>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link" href="stores.php">
          <span class="menu-icon">
            <i class="mdi mdi-store"></i>
          </span>
          <span class="menu-title">Stores</span>
        </a>
      </li>
      <li class="nav-item menu-items">
        <a class="nav-link" href="store_items.php">
          <span class="menu-icon">
            <i class="mdi mdi-gamepad"></i>
          </span>
          <span class="menu-title">Store Items</span>
        </a>
      </li>
    <?php } ?>
    <li class="nav-item nav-category">
      <span class="nav-link">Donation Section</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="donations.php">
        <span class="menu-icon">
          <i class="mdi mdi-plus-circle"></i>
        </span>
        <span class="menu-title">Donations</span>
      </a>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link">Votes Section</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="votes.php">
        <span class="menu-icon">
          <i class="mdi mdi-note"></i>
        </span>
        <span class="menu-title">Votes</span>
      </a>
    </li>
    <?php if (isAdmin()) { ?>
      <li class="nav-item menu-items">
        <a class="nav-link" href="vote_links.php">
          <span class="menu-icon">
            <i class="mdi mdi-link"></i>
          </span>
          <span class="menu-title">Vote Links</span>
        </a>
      </li>
    <?php } ?>
  </ul>
</nav>