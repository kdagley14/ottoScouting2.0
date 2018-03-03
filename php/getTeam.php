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
$sql = "SELECT * FROM matchSchedule WHERE match_num = $_POST[matchNum]";

if ($result = $conn->query($sql)) {

    if ($result->num_rows > 1) {
        echo "Error: There are multiple matches with the same match_num";
    }
    /* fetch associative array */
    $row = $result->fetch_assoc();
    $array["red_1"] = $row["red_1"];
    $array["red_2"] = $row["red_2"];
    $array["red_3"] = $row["red_3"];
    $array["blue_1"] = $row["blue_1"];
    $array["blue_2"] = $row["blue_2"];
    $array["blue_3"] = $row["blue_3"];
    echo json_encode($array);
    //"<input type='text' id='team_num' value='" . $row[$_POST['team']] . "'>";

    /* free result set */
    $result->free();
}

// close database connection
$conn->close();

?>
