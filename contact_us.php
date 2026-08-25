<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - CTU BSIT Profiling</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="homepage.css">
  
  <style>
    /* Glassmorphism CSS */
    body {
     
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Poppins', sans-serif;
    }

    .containerz {
      width: 50%;
      height: 60%;
      max-width: 60%;
      padding: 6rem;
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.15);
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      color: white;
      text-align: center;
      margin-top: 5%;
      margin-right: -3%;

    
    }

    h1 {
      font-size: 4rem;
      margin-bottom: 1rem;
      font-weight: 600;
      margin-top: -3%;
    }

    p {
      font-size: 1.4rem;
      line-height: 1.6;
      margin-bottom: 3rem;
      margin-top: -9%;
      margin: 3px;

    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center;
margin: 20px;


}
    input, textarea {
      width: 100%;
      max-width: 500%;
      padding: 1rem;
      margin-bottom: 1rem;
      border: none;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      font-size: 1rem;
      outline: none;
      backdrop-filter: blur(10px);
    height: 7vh;


    }

    input::placeholder, textarea::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    button {
      padding: 0.7rem 2rem;
      font-size: 1.5rem;
      font-weight: 600;
      color: white;
      border: none;
      border-radius: 25px;
      background: linear-gradient(90deg, #ff7eb3, #ff758c);
      box-shadow: 0 4px 15px rgba(255, 117, 140, 0.4);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
    }

    button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(255, 117, 140, 0.6);
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
  <div class="containerz">
    <h1>Contact Us</h1>
    <p>
      If you have any questions or need assistance, 
      please feel free to reach out to us.
    </p>
    <form action="#" method="post">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
</body>
</html>
