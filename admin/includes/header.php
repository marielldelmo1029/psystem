<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" >
  <div class="container-fluid" style="background: lightblue;" >


  <div style="background: #ffffff; box-shadow: inset 3px 3px 6px rgba(0, 0, 0, 0.1), inset -3px -3px 6px rgba(255, 255, 255, 0.8); border-radius: 50%; padding: 5px;">
        <img src="images/bsitlogo.jpg" alt="Logo" style="height: 40px; border-radius:20px;"> <!-- Replace with the actual logo path -->
      </div>
    <a class="navbar-brand" href="#" style="color: #003366; font-weight: bold;">Infotech Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto"> <!-- ms-auto to align items to the right -->
        <!-- Dashboard Link -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Admin</a>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i> <?php echo $_SESSION['sturecmsaid']; ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="change_password.php">Settings</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

