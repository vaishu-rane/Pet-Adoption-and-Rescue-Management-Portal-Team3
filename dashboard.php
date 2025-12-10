<?php 
include "session_protect.php"; 
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body {
  font-family: Arial, sans-serif;
  background:#ffece7;
  padding:40px;
}
.box {
  max-width:600px;
  margin:auto;
  background:white;
  padding:30px;
  border-radius:18px;
  box-shadow:0 5px 20px rgba(0,0,0,0.15);
  text-align:center;
}
.btn {
  padding:10px 22px;
  background:#ff6f61;
  color:white;
  text-decoration:none;
  border-radius:10px;
  font-weight:bold;
}
input[type=file] {
  margin-top:15px;
}
.result-box {
  margin-top:20px;
  padding:15px;
  background:#fff4f2;
  border-radius:12px;
  text-align:center;
}
.result-img {
  width:200px;
  border-radius:12px;
  margin-top:10px;
}
</style>
</head>

<body>

<div class="box">
  <h2>Welcome, <?php echo $_SESSION['user']; ?> 👋</h2>
  <p>You have successfully logged in to PetRescue.</p>

  <!-- Upload image for matching -->
  <h3>Upload an Image to Identify a Pet</h3>

  <form action="" method="POST" enctype="multipart/form-data">
      <input type="file" name="pet_image" required>
      <br><br>
      <button class="btn" type="submit" name="upload">Match Pet</button>
  </form>

  <br>
  <a class="btn" href="logout.php">Logout</a>

  <!-- Result Section -->
  <?php
  if(isset($_POST['upload'])){

      $fileName = $_FILES['pet_image']['name'];
      $fileTmp = $_FILES['pet_image']['tmp_name'];

      $uploadPath = "uploads/" . $fileName;

      // Move uploaded image to uploads/
      if(move_uploaded_file($fileTmp, $uploadPath)){

          // Run Python matching script
          $command = "python match.py " . escapeshellarg($uploadPath);
          $output = shell_exec($command);

          if($output){
              list($matchedPath, $accuracy) = explode(" ", trim($output));

              echo "<div class='result-box'>";
              echo "<h3>Matched Pet:</h3>";
              echo "<p><strong>Accuracy:</strong> ". round($accuracy * 100, 2) ."%</p>";
              echo "<img src='$matchedPath' class='result-img'>";
              echo "</div>";
          } else {
              echo "<p style='color:red;'>Error running Python script.</p>";
          }
      } else {
          echo "<p style='color:red;'>Failed to upload image!</p>";
      }
  }
  ?>

</div>

</body>
</html>
