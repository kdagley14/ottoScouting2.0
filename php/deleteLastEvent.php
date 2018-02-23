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
$sql = "DELETE FROM $_POST[table] WHERE id = (
            SELECT maxId FROM (
                SELECT max(id) as maxId
                FROM $_POST[table]
                WHERE team_num = $_POST[teamNum]
                AND match_num = $_POST[matchNum]
            ) temp
        )";

// execute SQL query
if ($conn->query($sql) === TRUE) {
    echo "Last event deleted successfully";
} else {
    echo "Error: " . $sql . "\n\n" . $conn->error;
}
// close database connection
$conn->close();

?>
