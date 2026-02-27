<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    $to = "jason.perinbam@hotmail.com";

    $headers  = "From: CVPilot <jason.perinbam@hotmail.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    $fullMessage = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    if (mail($to, $subject, $fullMessage, $headers)) {
        $notification = "success";
    } else {
        $notification = "error";
    }
}
?>

<script>
    window.addEventListener("load", function () {
      const container = document.getElementById("notification-container");
      <?php if (!empty($notification) && $notification === "success"): ?>
          showNotification("Message sent successfully!", container);
      <?php elseif (!empty($notification) && $notification === "error"): ?>
          showNotification("Failed to send message. Try again.", container);
      <?php endif; ?>
  });

  function showNotification(text, container) {
      const note = document.createElement("div");
      note.className = "notification";
      note.textContent = text;
      container.appendChild(note);

      setTimeout(() => note.classList.add("show"), 50);
      setTimeout(() => note.classList.remove("show"), 3000);
      setTimeout(() => note.remove(), 3500);
  }
</script>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <nav class="navbar navy">
    <div class="nav-container">
      <div class="nav-logo-container">
        <h1 class="logo"><a href="index.php">CVPilot</a></h1>
      </div>
      <div class="nav-links-container">
        <ul class="nav-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="help.php">Help</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="nav-spacer"></div>

  <section class="contact-section">
    <h1 class="contact-title">Contact Us</h1>
    <p class="contact-body">
      Fill out the form below and get in touch with us directly. Whether it's a question, need technical support, 
      or want to share feedback, we’re here!
    </p>

    <form class="contact-form" action="contact.php" method="POST">
      <div class="form-group">
        <input type="text" id="name" name="name" placeholder="Full Name" required>
      </div>

      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Email Address" required>
      </div>

      <div class="form-group">
        <input type="text" id="subject" name="subject" placeholder="Subject" required>
      </div>

      <div class="form-group">
        <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
      </div>

      <button type="submit" class="contact-btn">Send Message</button>
    </form>

  </section>
  
  <div id="notification-container"></div>

  <script src="script.js"></script>

  <footer class="footer">
    <div class="footer-container">
      <nav class="footer-links main-links">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="help.php">Help</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </nav>

      <nav class="footer-links legal-links">
        <ul>
          <li><a href="privacy.php">Privacy Policy</a></li>
          <li>•</li>
          <li><a href="terms.php">Terms & Conditions</a></li>
        </ul>
      </nav>

      <p class="footer-text">© 2025 CVPilot. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
