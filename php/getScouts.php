<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "ottoscouting2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM scouts";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $index = 0;
        while($row = $result->fetch_assoc()) {
            $array[$index] = $row['name'];
            $index += 1;
        }
        echo json_encode($array);
    } else {
        echo "No Scouts Found";
    }
    $result->free();
}

$conn->close();
?>
