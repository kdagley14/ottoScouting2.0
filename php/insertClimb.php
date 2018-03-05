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
$sql = "INSERT INTO climbs (
        team_num,
        match_num,
        type,
        position,
        match_time,
        auton,
        x,
        y
    ) VALUES (
        '$_POST[teamNum]',
        '$_POST[matchNum]',
        '$_POST[type]',
        POINTFROMTEXT('$_POST[position]'),
        '$_POST[matchSeconds]',
        '$_POST[auton]',
        '$_POST[x]',
        '$_POST[y]'
);";

$result = $conn->query($sql);

// execute SQL query
if ($result === TRUE) {
    echo $result;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
// close database connection
$conn->close();

?>
