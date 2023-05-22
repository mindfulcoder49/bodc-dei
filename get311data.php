<?php
$config = include('../config/config.php');

$servername = $config['db']['host'];
$username = $config['db']['user'];
$password = $config['db']['pass'];
$dbname = $config['db']['name'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM predictions";
$result = $conn->query($sql);
$data = array();

$maxIterations = isset($_GET['numrecords']) ? intval($_GET['numrecords']) : 100;  // Get the limit from URL parameter

if ($result->num_rows > 0) {
  // Output data of each row
  $counter = 0;  // Initialize the counter variable
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    
    // Increment the counter
    $counter++;
    
    // Break the loop if the counter reaches the maximum iterations
    if ($counter >= $maxIterations) {
      break;
    }
  }
}


echo json_encode($data);

$conn->close();
?>