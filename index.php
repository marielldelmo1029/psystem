<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="homepage.css">
    <title>Portfolio Website</title>
    <style>
      body{
    background-color: #007bff;
      }
    </style>
</head>
<body>
    <header>
    <div class="hamburger" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
  <div class="headercontainer">

        <nav>
            <a href="index.php" class="active"> Home</a>
            <a href="about_us.html" >About</a>
            <a href="contact_us.php" >Contact Us</a>
            <a href="student/register.php" >Sign Up</a>
            <a href="student/login.php" >Sign In</a>

        </nav>
</div>
    </header>

   
    <script src="homepage.js"></script>
    <section class="home">
    <div class="home-img">
        <img src="main.jpg" alt="">
    </div>
    <div class="picture">
        <img src="images/student.png" alt="description" width="200px" height="200px">
    </div>
    <!-- Glass container positioned behind the content -->
    <div class="glass"></div>

    <div class="home-content">
        <h1>Welcome to <span><b>CTU</b></span></h1>
        <h3 class="typing-text">Profiling System <span></span></h3>
        <p>Modernizing the way BSIT student profiles are managed for greater success and clarity.</p>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-google"></i></a>
        </div>
        <a href="student/login.php" class="btn">Login</a>
    </div>
</section>


    
</body>
</html>