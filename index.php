<!DOCTYPE html>
<script src="jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
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
            <h5>Match Number</h5>
            <div class="row">
                <input id="matchNum" class="col s3" type="number">
            </div>
            <h5 id="allianceHeader">Alliance</h5>
            <div class="row">
                <button class="btn" id="red" onclick="changeAlliance('red')">Red Alliance</button>
                <button class="btn" id="blue" onclick="changeAlliance('blue')">Blue Alliance</button>
            </div>
            <h5 id="teamSlotHeader">Team Slot</h5>
            <div class="row">
                <button class="btn" id="team1" onclick="changeTeamSlot(1)">1</button>
                <button class="btn" id="team2" onclick="changeTeamSlot(2)">2</button>
                <button class="btn" id="team3" onclick="changeTeamSlot(3)">3</button>
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
                <input id="startMatch" class="btn red" type="submit" value="Start Match">
            </form>
        </div>
    </div>
</div>
</body>

<script>
    function showDiv() {
        document.getElementById('powerCubeDiv').style.display = "block";
    }

    function changeAlliance(color) {
        $('#alliance').val(color);
        $('#allianceHeader').html("Alliance - " + color.toUpperCase());
    }

    function changeTeamSlot(num) {
        $('#teamSlot').val(num);
        $('#teamSlotHeader').html("Team Slot - " + num);
    }

    var teamPopup = document.getElementById('teamPopup');
    var getTeamBtn = document.getElementById('getTeam');


    $('#botCube').on('click', function(e) {
        $('#hasCubeMsg').text("Robot IS starting with power cube");
        $('#has_cube').val("start_with_cube");
        $('#start').show();
    });

    $('#botNoCube').on('click', function(e) {
        $('#hasCubeMsg').text("Robot IS NOT starting with power cube");
        $('#has_cube').val("start_no_cube");
        $('#start').show();
    });

    // Get the team from the match schedule in the database
    $('#getTeam').on('click', function(e) {
        e.preventDefault();
        var alliance = document.getElementById('alliance').value;
        var teamSlot = document.getElementById('teamSlot').value;
        var matchNum = document.getElementById('matchNum').value;
        if (alliance == "" || teamSlot == "" || matchNum == "") {
            $('#error').html("Please fill out all the fields.");
        } else {
            document.getElementById('match_num').value = matchNum;
            $.ajax({
                url: 'php/getTeam.php',
                type: 'POST',
                dataType:'json',
                data: {
                    matchNum: matchNum
                },
                success: function(response) {
                    var team = alliance + "_" + teamSlot;
                    var element = "<input type='hidden' name='team_num' id='team_num' value='" + response[team] + "'>";
                    if (alliance == "red") {
                        element += "<input type='hidden' name='oppTeam1' id='oppTeam1' value='" + response["blue_1"] + "'><input type='hidden' name='oppTeam2' id='oppTeam2' value='" + response["blue_2"] + "'><input type='hidden' name='oppTeam3' id='oppTeam3' value='" + response["blue_3"] + "'>";
                    } else {
                        element += "<input type='hidden' name='oppTeam1' id='oppTeam1' value='" + response["red_1"] + "'><input type='hidden' name='oppTeam2' id='oppTeam2' value='" + response["red_2"] + "'><input type='hidden' name='oppTeam3' id='oppTeam3' value='" + response["red_3"] + "'>";
                    }
                    $('#display_team').html(element);
                    $('#scoutingTeam').text("You are scouting Team " + response[team]);
                    $('#teamPopup').hide();
                    document.getElementById('robotPic').src = "images/" + response[team] + ".jpg";
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
        }
    });

    // Add the starting position into the database as a pcEvent
    $('#startPos').on('click', function(e) {
        var pos = document.getElementById('position').value;
        var startEvent = document.getElementById('has_cube').value;
        var matchNum = document.getElementById('match_num').value;
        var teamNum = document.getElementById('team_num').value;
        var x = document.getElementById('x').value;
        var y = document.getElementById('y').value;

        var alliance = document.getElementById('alliance').value;
        if (alliance == "blue") {
            x = 850 - x;
            y = 431 - y;
            pos = "POINT(" + x + " " + y + ")";
        }
        
        $.ajax({
            url: 'php/insertPCEvent.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                type: startEvent,
                position: pos,
                x: x,
                y: y
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
        $('#startPos').hide();
        $('#form').show();
    });

</script>

</html>
