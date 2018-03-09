<!DOCTYPE html>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/materialize.css">
</head>
<body>

<div class="container">
    <div id="teamPopup" class="modal" style="display:block;">
        <div class="modal-content">
            <h4 class="center-align">Choose the Assigned Scouting Location</h4>
            <h5>Scout Name</h5>
            <div class="row input-field">
                <select id="display_scouts">
                </select>
            </div>
            <h5>Match Number</h5>
            <div class="row">
                <input id="matchNum" class="col s3" type="number">
            </div>
            <h5 id="allianceHeader">Alliance</h5>
            <div class="row">
                <button class="btn" id="red">Red Alliance</button>
                <button class="btn" id="blue">Blue Alliance</button>
            </div>
            <h5 id="teamSlotHeader">Team Slot</h5>
            <div class="row">
                <button class="btn" id="team1">1</button>
                <button class="btn" id="team2">2</button>
                <button class="btn" id="team3">3</button>
            </div>
            <h5 style="color:red;" id="error"></h5>
            <button class="btn center-align" id="getTeam">Save</button>
        </div>
    </div>

    <div class="center-align row">
        <h4 id="scoutingTeam">You are scouting Team</h4>
    </div>

    <div class="center-align row">
        <img id="robotPic" onerror="this.src='images/error.jpg'" style="max-height:500px;max-width:500px;">
    </div>

    <div class="center-align section">
        <h5>Select the robot's starting position:</h5>
    </div>

    <div id="field" class="field">
        <canvas id="fieldPic" class="responsive-img" height="431" width="850">
            <script>
                var canvas = document.getElementById("fieldPic");
                var context = canvas.getContext("2d");
                var img = new Image();
                img.onload = function () {
                    context.drawImage(img, 0, 0, 850, 431);
                }
                img.src = "images/field.png";
            </script>
        </canvas>
        <canvas id="botPosition" class="responsive-img" height="431" width="850">
            <script>
                // Make canvas listen for clicks and do a thing when clickied.
                var botCanvas = document.getElementById("botPosition");
                botCanvas.addEventListener('mousedown', function(event) {
                    // Init variables when canvas be clicked
                    var canvas = document.getElementById("botPosition");
                    var context = canvas.getContext('2d');
                    var rect = canvas.getBoundingClientRect();

                    var xTemp = (event.clientX - rect.left);
                    var yTemp = (event.clientY - rect.top);
                    var clickX = (event.clientX - rect.left);
                    var clickY = (event.clientY - rect.top);
                    var xPos = parseInt(xTemp);
                    var yPos = parseInt(yTemp);

                    // Draw the dot marking the robot
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    context.beginPath();
                    context.arc(clickX, clickY, 12, 0, Math.PI*2);
                    context.fillStyle = '#FFFF00';
                    context.fill();
                    context.lineWidth = 3;
                    context.strokeStyle = '#B2B200';
                    context.stroke();
                    context.closePath();

                    document.getElementById('position').value = "POINT(" + xPos + " " + yPos + ")";
                    document.getElementById('x').value = xPos;
                    document.getElementById('y').value = yPos;
                    $('#afterPos').show();
                });
            </script>
        </canvas>
    </div>
    <input id="position" type="hidden" name="position">

    <br>
    <div id="afterPos" style="display:none;">
        <div class="center-align section">
            <h5>Is the robot starting with a power cube?</h5>
            <button id="botCube" class="btn">Yes</button>
            <button id="botNoCube" class="btn">No</button>
            <h6 id="hasCubeMsg"></h6>
        </div>

        <input id="x" type="hidden" name="x" value="1">
        <input id="y" type="hidden" name="y" value="1">
        <div id="start" class="center-align" style="display:none;">
            <button id="startPos" class="btn">Save</button>
            <form id="form" action="match.php" method="post" class="center-align section" style="display:none;">
                <div id=display_team></div>
                <input name="has_cube" id="has_cube" type="hidden">
                <input name="match_num" id="match_num" type="hidden">
                <input type="hidden" id="alliance" name="alliance">
                <input type="hidden" id="teamSlot" name="teamSlot">
                <input type="hidden" id="scoutName" name="scoutName">
                <input id="startMatch" class="btn red" type="submit" value="Start Match">
            </form>
        </div>
    </div>
</div>
</body>

<script src="jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script src="js/prematch.js"></script>
<script>
    var $_POST = <?php echo json_encode($_POST); ?>;

    // Try to autopopulate match number, alliance, and team slot if possible
    if ($_POST["match_num"] != null) {
        $('#matchNum').val(parseInt($_POST["match_num"], 10) + 1);
    }
    if ($_POST["alliance"] != null) {
        $('#alliance').val($_POST["alliance"]);
        if ($_POST["alliance"] == "red") {
            $('#red').addClass("teal darken-3");
        } else {
            $('#blue').addClass("teal darken-3");
        }
    }
    if ($_POST["teamSlot"] != null) {
        $('#teamSlot').val($_POST["teamSlot"]);
        if ($_POST["teamSlot"] == "1") {
            $('#team1').addClass("teal darken-3");
        } else if($_POST["teamSlot"] == "2") {
            $('#team2').addClass("teal darken-3");
        } else {
            $('#team3').addClass("teal darken-3");
        }
    }

    // Load the scout names from the database
    $(document).ready(function() {
        $.ajax({
            url: 'php/getScouts.php',
            type: 'POST',
            dataType:'json',
            success: function(response) {
                // Try to autopopulate name
                var element;
                var scout;
                if ($_POST["scoutName"] != null) {
                    scout = $_POST["scoutName"];
                } else {
                    element += "<option id='no_name' value='' disabled selected>Select your name</option>";
                }

                for(var i = 0; i < response.length; i++) {
                    var name = response[i];
                    element += "<option id='" + name.split(" ").join("") + "' value='" + name + "'";
                    if (name == scout) {
                        element += " selected";
                    }
                    element +=">" + name + "</option>";
                }
                $('#display_scouts').append(element);
                $('select').material_select();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
    });
</script>

</html>
