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
$sql = "SELECT type FROM (
            SELECT type, max(match_time) FROM (
                (SELECT type, max(match_time) FROM pcEvents GROUP BY type) UNION ALL
                (SELECT type, max(match_time) FROM fouls GROUP BY type) UNION ALL
                (SELECT type, max(match_time) FROM breakdowns GROUP BY type)
            ) d
            GROUP BY type
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
