<!DOCTYPE html>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/materialize.css">
</head>
<body>
<div class="container">
    <div id="foulPopup" class="modal">
        <div class="modal-content">
            <div class="row">
                <h4 class="center-align">Fouls</h4>
            </div>
            <br>
            <div class="row center-align">
                <button id="general" class="btn foul">General</button>
                <button id="yellow_card" class="btn foul yellow accent-4">Yellow Card</button>
                <button id="red_card" class="btn foul red">Red Card</button>
            </div>
        </div>
    </div>

    <div id="breakdownPopup" class="modal">
        <div class="modal-content">
            <div class="row">
                <h4 class="center-align">Breakdowns</h4>
            </div>
            <div class="row center-align">
                <button id="partial" class="btn breakdown">Partial Breakdown</button>
                <button id="never_moved" class="btn breakdown">Never Moved</button>
            </div>
            <div class="row center-align">
                <button id="lost_parts" class="btn breakdown">Lost Parts</button>
                <button id="no_auto" class="btn breakdown">No Autonomous</button>
            </div>
            <div class="row center-align">
                <button id="no_show" class="btn breakdown">Didn't Show Up</button>
                <button id="intermittent" class="btn breakdown">Intermittent Breakdowns</button>
            </div>
            <div class="row center-align">
                <button id="fell_over" class="btn breakdown">Fell Over</button>
            </div>
        </div>
    </div>

    <div id="climbPopup" class="modal">
        <div class="modal-content">
            <div class="row">
                <h4 class="center-align">Climbs</h4>
            </div>
            <div class="row center-align">
                <button id="self_climb_success" class="btn green climb">Climbed by Itself: Success</button>
                <button id="self_climb_fail" class="btn red climb">Climbed by Itself: Failure</button>
            </div>
            <div class="row center-align">
                <button id="hang_climb_success" class="btn green climb">Hung off Another Bot: Success</button>
                <button id="hang_climb_fail" class="btn red climb">Hung off Another Bot: Failure</button>
            </div>
            <div class="row center-align">
                <button id="platform_success" class="btn green climb">Finished on Platform: Success</button>
                <button id="platform_fail" class="btn red climb">Didn't Finish on Platform: Failure</button>
            </div>
        </div>
    </div>

    <div class="row">
        <h4 id="scoutingTeam">You are scouting Team</h4>
    </div>

    <div class="row">
        <h5 id="timer" class="col s8">Match Time: 00:00:00</h5>
        <h5 id="last_event" class="col s4">Last Event: </h5>
    </div>

    <div class="row">
        <div class="col s6">
            <button id="auton_done" class="btn red pulse">End Autonomous</button>
            <form action="postmatch.php" method="post">
                <?php
                   foreach($_POST as $key=>$value) { ?>
                       <input id="<?php echo $key?>" type="hidden" name="<?php echo $key?>" value="<?php echo $value?>"/>
                <?php } ?>
                <button id="end_match" class="btn orange" type="submit" onclick="return confirm('Are you sure you want to end the match?');">End Match</button>
            </form>
        </div>
        <div class="col s6 right-align">
            <button id="defended" class="btn modal-trigger reset">Defended</button>
            <button id="foul" data-target="foulPopup" class="btn orange modal-trigger reset">Foul</button>
            <button id="breakdown" data-target="breakdownPopup" class="btn red modal-trigger reset">Breakdown</button>
        </div>
    </div>

    <div id="field" class="field">
        <canvas id="fieldPic" height="431" width="850">
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
        <canvas id="botPosition" height="431" width="850">
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

                    var lastPCEvent = document.getElementById('last_pc_event').value;
                    if (lastPCEvent == "start_with_cube" || lastPCEvent.indexOf("pick") != -1) {
                        document.getElementById("pick").disabled = true;
                        document.getElementById("place").disabled = false;
                        document.getElementById("drop").disabled = false;
                    } else {
                        document.getElementById("pick").disabled = false;
                        document.getElementById("place").disabled = true;
                        document.getElementById("drop").disabled = true;
                    }
                    document.getElementById("foul").disabled = false;
                    document.getElementById("breakdown").disabled = false;
                    document.getElementById("defended").disabled = false;
                });
            </script>
        </canvas>
    </div>
    <br>

    <!-- Hidden inputs so we can easily store/access these values -->
    <input id="type" type="hidden" name="type">
    <input id="position" type="hidden" name="position" value="POINT(1 1)">
    <input id="x" type="hidden" name="x" value="1">
    <input id="y" type="hidden" name="y" value="1">
    <input id="last_table" type="hidden" name="last_table">
    <input id="match_seconds" type="hidden" name="match_seconds">
    <input id="auton" type="hidden" name="auton" value="yes">
    <input id="last_pc_event" type="hidden" name="hidden">

    <div class="section row center-align">
        <button id="pick" class="btn light-green reset">Pick</button>
        <button id="place" class="btn light-green reset" disabled>Place</button>
        <button id="drop" class="btn red reset" disabled>Drop</button>
    </div>
</div>
</body>

<script src="jquery-3.3.1.js"></script>
<script src="easytimer/dist/easytimer.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script src="js/match.js"></script>

</html>
