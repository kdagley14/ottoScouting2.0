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

// sql query
$sql = "SELECT $_POST[team] FROM matchSchedule WHERE match_num = $_POST[matchNum]";

if ($result = $conn->query($sql)) {

    if ($result->num_rows > 1) {
        echo "Error: There are multiple matches with the same match_num";
    }
    /* fetch associative array */
    $row = $result->fetch_assoc();
    $array[$_POST['team']] = $row[$_POST['team']];
    echo json_encode($array);
    
    //"<input type='text' id='team_num' value='" . $row[$_POST['team']] . "'>";

    /* free result set */
    $result->free();
}

// close database connection
$conn->close();

?>
