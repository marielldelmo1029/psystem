<style>
    .sidebar {
        position: -webkit-sticky;  /* For Safari */
        position: sticky;
        top: 0;  /* Keeps the sidebar at the top of the screen */
        height: 100vh;  /* Make the sidebar take the full height of the viewport */
        overflow-y: auto;  /* Enables scrolling within the sidebar if needed */
  
  }
</style>

<div class="col-md-3 col-lg-2 sidebar">
   <br>
    <nav class="mt-3">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
          
            <!-- Manage Students (Dropdown)-->
            <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php' || basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-bullhorn"></i> Manage Students
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : ''; ?>" href="search.php">Search</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_students.php') ? 'active' : ''; ?>" href="manage_students.php">Manage Student Lists</a></li>
                </ul>
            </li>  


          
 <!-- Manage Students (Dropdown) 
 <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'add_students.php' || basename($_SERVER['PHP_SELF']) == 'manage_public_notice.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-bullhorn"></i> Manage Student
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_students.php') ? 'active' : ''; ?>" href="manage_students.php">Student Lists</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'regular_students.php') ? 'active' : ''; ?>" href="regular_students.php">Regular</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'irregular_students.php') ? 'active' : ''; ?>" href="irregular_students.php">Irregular</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'shift_students.php') ? 'active' : ''; ?>" href="shift_students.php">Shift</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'drop_students.php') ? 'active' : ''; ?>" href="drop_students.php">Drop</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'graduate_students.php') ? 'active' : ''; ?>" href="graduate_students.php">Graduate</a></li>

                </ul>

                </li>
                <li class="nav-item">
                <a href="manage_students.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_students.php') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-out-alt"></i> Manage Students
                </a>
            </li>-->

            
            </li>
            </li>
                <li class="nav-item">
                <a href="create_student_form.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'create_student_form.php') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-out-alt"></i> Create Student Form
                </a>
            </li>
              <!-- Manage Class (Dropdown) 
              <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'add_students.php' || basename($_SERVER['PHP_SELF']) == 'manage_public_notice.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-bullhorn"></i> Manage Classes
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'add_students.php') ? 'active' : ''; ?>" href="add_class.php">Add class</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_class.php') ? 'active' : ''; ?>" href="manage_class.php">Manage Class Lists</a></li>
                </ul>-->
            </li>
            <!-- Manage Announcement (Dropdown) -->
            <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_notice.php' || basename($_SERVER['PHP_SELF']) == 'manage_public_notice.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-bullhorn"></i>Announcement
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'add_notice.php') ? 'active' : ''; ?>" href="add_notice.php">Add Announcement</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_notice.php') ? 'active' : ''; ?>" href="manage_notice.php">Manage Announcement</a></li>
                </ul>
            </li>
              <!-- Manage Public Announcement (Dropdown) -->
              <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_notice.php' || basename($_SERVER['PHP_SELF']) == 'manage_public_notice.php') ? 'active' : ''; ?>">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-bullhorn"></i> Public Notice
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'add_public_notice.php') ? 'active' : ''; ?>" href="add_public_notice.php">Add Announcement</a></li>
                    <li><a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_public_notice.php') ? 'active' : ''; ?>" href="manage_public_notice.php">Manage Public Notice</a></li>
                </ul>
            </li>
        
             
            <!-- Logout -->
            <li class="nav-item">
                <a href="logout.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</div>
