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

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"X-TBA-Auth-Key: a4E8BMLx2hWwm8jWG8RFnS3AixOYXAuY3RQMxJFRrgqtt4f1lHA8YF4lFxfTEdi1"
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$json = file_get_contents('http://www.thebluealliance.com/api/v3/event/2018gagai/matches', false, $context);
$data = json_decode($json);


$conn->query('TRUNCATE TABLE matchSchedule;');

foreach($data as $key => $value) {
  if($value->comp_level == 'qm'){

    //Create team number variables
    $red_1 = substr($value->alliances->red->team_keys[0], 3);
    $red_2 = substr($value->alliances->red->team_keys[1], 3);
    $red_3 = substr($value->alliances->red->team_keys[2], 3);
    $blue_1 = substr($value->alliances->blue->team_keys[0], 3);
    $blue_2 = substr($value->alliances->blue->team_keys[1], 3);
    $blue_3 = substr($value->alliances->blue->team_keys[2], 3);

    $sql = "INSERT INTO matchSchedule (match_num,red_1,red_2,red_3,blue_1,blue_2,blue_3)
    VALUES ($value->match_number,$red_1,$red_2,$red_3,$blue_1,$blue_2,$blue_3);";

    // execute SQL query
    if ($conn->query($sql) === TRUE) {
        echo "New record $value->match_number created successfully <br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
};

$conn->query('ALTER TABLE matchSchedule ORDER BY match_num ASC;');


// close database connection
$conn->close();

?>
