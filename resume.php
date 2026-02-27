<?php
session_start();
require_once __DIR__ . '/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['export_pdf'])) {

    $_SESSION['fname'] = $_POST['fname'] ?? '';
    $_SESSION['lname'] = $_POST['lname'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';
    $_SESSION['phone'] = $_POST['phone'] ?? '';

    $_SESSION['summary']   = $_POST['summary'] ?? '';
    $_SESSION['work']      = $_POST['work'] ?? '';
    $_SESSION['skills']    = $_POST['skills'] ?? '';
    $_SESSION['education'] = $_POST['education'] ?? '';

    $_SESSION['exclude_work']      = isset($_POST['exclude_work']);
    $_SESSION['exclude_education'] = isset($_POST['exclude_education']);
}

$fname = $_SESSION['fname'] ?? '';
$lname = $_SESSION['lname'] ?? '';
$email = $_SESSION['email'] ?? '';
$phone = $_SESSION['phone'] ?? '';

$summary   = $_SESSION['summary'] ?? '';
$work      = $_SESSION['work'] ?? '';
$skills    = $_SESSION['skills'] ?? '';
$education = $_SESSION['education'] ?? '';

$exclude_work      = $_SESSION['exclude_work'] ?? false;
$exclude_education = $_SESSION['exclude_education'] ?? false;


if (isset($_POST['export_pdf'])) {

    try {
        $summary   = nl2br(htmlspecialchars($_SESSION['summary'] ?? ''));
        $work      = nl2br(htmlspecialchars($_SESSION['work'] ?? ''));
        $education = nl2br(htmlspecialchars($_SESSION['education'] ?? ''));
        $skillsRaw = $_SESSION['skills'] ?? '';

        $skillsList = '';
        $skillsArray = preg_split('/[\n,]+/', $skillsRaw);

        foreach ($skillsArray as $skill) {
            $skill = trim($skill);
            if ($skill !== '') {
                $skillsList .= "<li>" . htmlspecialchars($skill) . "</li>";
            }
        }

        $html = "
        <html>
        <head>
            <style>
                body { font-family: DejaVu Sans, sans-serif; }
                h1 { font-size: 26px; margin-bottom: 5px; }
                h2 { margin-top: 25px; border-bottom: 1px solid #ccc; }
                p { line-height: 1.5; }
                ul { padding-left: 20px; }
            </style>
        </head>
        <body>

            <h1>{$fname} {$lname}</h1>
            <p>{$email}<br>{$phone}</p>

            <h2>Professional Summary</h2>
            <p>{$summary}</p>
        ";

        if (!$exclude_work && trim($work) !== '') {
            $html .= "
            <h2>Work Experience</h2>
            <p>{$work}</p>
            ";
        }

        if (!$exclude_education && trim($education) !== '') {
            $html .= "
            <h2>Education</h2>
            <p>{$education}</p>
            ";
        }

        if (!empty($skillsList)) {
            $html .= "
            <h2>Skills</h2>
            <ul>{$skillsList}</ul>
            ";
        }

        $html .= "
        </body>
        </html>
        ";


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment; filename=\"{$fname}_{$lname}_CV.pdf\"");
        echo $dompdf->output();
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Resume Builder</title>
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

  <main class="resume-builder">

    <section class="form-column">

      <h2>Build Your Resume</h2>
      <form method="POST">


        <h3>Your Details</h3>

        <div class="your-details">
          <input type="text" name="fname" placeholder="First Name*" value="<?php echo htmlspecialchars($fname); ?>" required>
          <input type="text" name="lname" placeholder="Last Name*" value="<?php echo htmlspecialchars($lname); ?>" required>
          <input type="email" name="email" placeholder="Email*" value="<?php echo htmlspecialchars($email); ?>" required>
          <input type="text" name="phone" placeholder="Phone (optional)" value="<?php echo htmlspecialchars($phone); ?>">
        </div>  

        <h3>Professional Summary</h3>
        <textarea id="summary" name="summary" placeholder="Write a professional summary of yourself..." ><?php echo htmlspecialchars($summary); ?></textarea>

        <h3>
          Work Experience
          <label class="checkbox-label">
            <input type="checkbox" name="exclude_work" <?php if ($exclude_work) echo "checked"; ?>>
            Exclude
          </label>
        </h3>
        <textarea id="work" name="work" placeholder="Write about your work experience..." <?php if ($exclude_work) echo "disabled"; ?>><?php echo htmlspecialchars($work); ?></textarea>

        <h3>
          Education
          <label class="checkbox-label">
            <input type="checkbox" name="exclude_education" <?php if ($exclude_education) echo "checked"; ?>>
            Exclude
          </label>
        </h3>
        <textarea id="education" name="education" placeholder="Write about your educational history..." <?php if ($exclude_education) echo "disabled"; ?>><?php echo htmlspecialchars($education); ?></textarea>

        <h3>Skills</h3>
        <textarea id="skills" name="skills" placeholder="List your skills and attributes..." ><?php echo htmlspecialchars($skills); ?></textarea>

        <div class="apply-reminder">Please press Apply Changes before exporting</div>

        <div class="form-buttons">
          <button type="submit" class="apply-btn">Apply Changes</button>
          <button type="button" id="exportBtn" name="export_pdf" class="apply-btn">Export Resume</button>
        </div>

      </form>
    </section>

    <section class="preview-column">
      <div class="resume-preview">

        <h2 id="preview-name"><?php echo htmlspecialchars("$fname $lname"); ?></h2>

        <p id="preview-contact">
          <?php echo htmlspecialchars($email); ?><br>
          <?php echo htmlspecialchars($phone); ?>
        </p>

        <hr>

        <h3>Professional Summary</h3>
        <p id="preview-summary">
          <?php echo $summary ? htmlspecialchars($summary) : "Your summary will appear here..."; ?>
        </p>

        <?php if (!$exclude_work): ?>
        <h3>Work Experience</h3>
        <p id="preview-work">
          <?php echo $work ? htmlspecialchars($work) : "Your work experience will appear here..."; ?>
        </p>
        <?php endif; ?>

        <?php if (!$exclude_education): ?>
        <h3>Education</h3>
        <p id="preview-education">
          <?php echo $education ? htmlspecialchars($education) : "Your education details will appear here..."; ?>
        </p>
        <?php endif; ?>

        <h3>Skills</h3>
        <p id="preview-skills">
          <?php echo $skills ? htmlspecialchars($skills) : "Your skills will appear here..."; ?>
        </p>

      </div>
    </section>

  </main>

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


<script>
document.getElementById("exportBtn").addEventListener("click", function () {

    const form = document.querySelector("form");
    const formData = new FormData(form);

    formData.append("export_pdf", "1");

    fetch("", {
        method: "POST",
        body: formData
    })

    .then(response => {
        if (!response.ok) throw new Error("Export failed");
        return response.blob();
    })

    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        const firstName = formData.get('fname') || 'FirstName';
        const lastName = formData.get('lname') || 'LastName';
        a.download = `${firstName}_${lastName}_CV.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);

        showNotification("CV exported successfully!");
    })
    .catch(() => {
        showNotification("Failed to export CV.", true);
    });
});


function showNotification(message, isError = false) {
    const container = document.getElementById("notification-container");
    const note = document.createElement("div");

    note.className = "notification" + (isError ? " error" : "");
    note.textContent = message;

    container.appendChild(note);

    setTimeout(() => note.classList.add("show"), 50);
    setTimeout(() => note.classList.remove("show"), 3000);
    setTimeout(() => note.remove(), 3500);
}
</script>