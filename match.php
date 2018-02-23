<!DOCTYPE html>
<script src="jquery-3.3.1.js"></script>
<script src="easytimer/dist/easytimer.min.js"></script>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="timer">00:00:00</div>
    <button id="auton_done">End Autonomous</button>
    <form>
        <button id="end_match" formaction="postmatch.php" onclick="return confirm('Are you sure you want to end the match?');">End Match</button>
    </form>
    <p id="last_event">Last Event: </p>
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
                });
            </script>
        </canvas>
    </div>

    <!-- Hidden inputs so we can easily store/access these values -->
    <input id="team_num" type="hidden" name="team_num" value="1746">
    <input id="match_num" type="hidden" name="match_num" value="1">
    <input id="type" type="hidden" name="type">
    <input id="position" type="hidden" name="position" value="POINT(1 1)">
    <input id="last_table" type="hidden" name="last_table">
    <input id="match_seconds" type="hidden" name="match_seconds">
    <input id="auton" type="hidden" name="auton" value="yes">

    <button id="pick" >Pick</button>
    <button id="throw_success" disabled>Throw Success</button>
    <button id="throw_fail" disabled>Throw Fail</button>
    <button id="place" disabled>Place</button>
    <button id="drop" disabled>Drop</button>
    <button id="foul">Foul</button>
    <button id="breakdown">Breakdown</button>
    <button id="undo">Undo Last Event</button>

    <div id="foulPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <button id="general">General</button>
            <button id="g05">G05: Robot overextended</button>
            <button id="g07">G07: Bumpers fell off</button>
            <button id="g09">G09: Launching a cube outside of the allowed zones</button>
            <button id="g14">G14: Pinning for more than 5 seconds</button>
            <button id="g15">G15: Robot camped in front of opponent's exchange zone</button>
            <button id="g16">G16: Robot made contact with opponent in null territory</button>
            <button id="g18">G18: Robot made contact with opponent in the platform zone</button>
            <button id="g22">G22: Robot possessed more than one power cube at a time</button>
            <button id="human">Human Did Something Wrong</button>
            <button id="yellow_card">Yellow Card</button>
            <button id="red_card">Red Card</button>
        </div>
    </div>

    <div id="breakdownPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <button id="partial">Partial Breakdown</button>
            <button id="never_moved">Never Moved</button>
            <button id="lost_parts">Lost Parts</button>
            <button id="no_auto">No Autonomous</button>
            <button id="no_show">Didn't Show Up</button>
            <button id="intermittent">Intermittent Breakdowns</button>
        </div>
    </div>

</body>

<script>
    var teamNum = document.getElementById('team_num').value;
    var matchNum = document.getElementById('match_num').value;
    $('#end_match').hide();

    /*************/
    /* T I M E R */
    /*************/

    var timer = new Timer();
    timer.start();
    timer.addEventListener('secondsUpdated', function (e) {
        $('#timer').html(timer.getTimeValues().toString());
        $('#match_seconds').val(timer.getTotalTimeValues().seconds);
    });


    $("#auton_done").on('click',function() {
        $(this).hide();
        $('#auton').val('no');
        $('#end_match').show();
    });


    /********************/
    /* P C  E V E N T S */
    /********************/

    // These enums need to match each button id for the for the code below this to
    // work properly
    var pcEvents = {
        pick: 'pick',
        throw_success: 'throw_success',
        throw_fail: 'throw_fail',
        place: 'place',
        drop: 'drop'
    };

    // For each loop to go through all of the pcEvents above and create an onclick event
    // for each of the corresponding buttons to submit to the database when the page is loaded
    for (let type in pcEvents) {
        $('#' + type).on('click', function(e) {
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            $.ajax({
                url: '/php/insertPCEvent.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: type,
                    position: pos,
                    matchSeconds: time,
                    auton: auton
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            document.getElementById('last_table').value = "pcEvents";
            document.getElementById('last_event').innerHTML = "Last Event: " + type;
            if(type == 'pick') {
                document.getElementById("pick").disabled = true;
                document.getElementById("throw_success").disabled = false;
                document.getElementById("throw_fail").disabled = false;
                document.getElementById("place").disabled = false;
                document.getElementById("drop").disabled = false;
            } else {
                document.getElementById("pick").disabled = false;
                document.getElementById("throw_success").disabled = true;
                document.getElementById("throw_fail").disabled = true;
                document.getElementById("place").disabled = true;
                document.getElementById("drop").disabled = true;
            }
        });
    }

    /*************/
    /* F O U L S */
    /*************/

    var foulPopup = document.getElementById('foulPopup');
    var foulBtn = document.getElementById("foul");
    var closeFouls = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the popup
    foulBtn.onclick = function() {
        foulPopup.style.display = "block";
    }
    // When the user clicks on <span> (x), close the popup
    closeFouls.onclick = function() {
        foulPopup.style.display = "none";
    }
    // When the user clicks anywhere outside of the popup, close it
    window.onclick = function(event) {
        if (event.target == foulPopup) {
            foulPopup.style.display = "none";
        }
    }

    var fouls = {
        general: 'general',
        g05: 'g05', // Robot overextended
        g07: 'g07', // Bumpers fell off
        g09: 'g09', // Launching a cube outside of the allowed zones
        g14: 'g14', // Pinning for more than 5 seconds
        g15: 'g15', // Robot camped in front of opponent's exchange zone
        g16: 'g16', // Robot made contact with opponent in null territory
        g18: 'g18', // Robot made contact with opponent in the platform zone
        g22: 'g22', // Robot possessed more than one power cube at a time
        human: 'human',
        yellow_card: 'yellow_card',
        red_card: 'red_card'
    };

    for (let type in fouls) {
        $('#' + type).on('click', function(e) {
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            $.ajax({
                url: '/php/insertFoul.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: type,
                    position: pos,
                    matchSeconds: time,
                    auton: auton
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            document.getElementById('last_table').value = "fouls";
            document.getElementById('last_event').innerHTML = "Last Event: " + type;
        });
    }

    /***********************/
    /* B R E A K D O W N S */
    /***********************/

    var breakdownPopup = document.getElementById('breakdownPopup');
    var breakdownBtn = document.getElementById("breakdown");
    var closeBreakdowns = document.getElementsByClassName("close")[1];

    // When the user clicks the button, open the popup
    breakdownBtn.onclick = function() {
        breakdownPopup.style.display = "block";
    }
    // When the user clicks on <span> (x), close the popup
    closeBreakdowns.onclick = function() {
        breakdownPopup.style.display = "none";
    }
    // When the user clicks anywhere outside of the popup, close it
    window.onclick = function(event) {
        if (event.target == breakdownPopup) {
            breakdownPopup.style.display = "none";
        }
    }

    var breakdowns = {
        partial: 'partial',
        never_moved: 'never_moved',
        lost_parts: 'lost_parts',
        no_auto: 'no_auto',
        no_show: 'no_show',
        intermittent: 'intermittent'
    };

    for (let type in breakdowns) {
        $('#' + type).on('click', function(e) {
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            $.ajax({
                url: '/php/insertBreakdown.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: type,
                    position: pos,
                    matchSeconds: time,
                    auton: auton
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            document.getElementById('last_table').value = "breakdowns";
            document.getElementById('last_event').innerHTML = "Last Event: " + type;
        });
    }

    /*******************************/
    /* U N D O  L A S T  E V E N T */
    /*******************************/

    $('#undo').on('click', function(e) {
        var lastTable = document.getElementById('last_table').value;
        $.ajax({
            url: '/php/deleteLastEvent.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                table: lastTable
            },
            success: function(msg) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });

        $.ajax({
            type: "GET",
            url: "php/getLastEvent.php",
            dataType: "text",
            success: function (response) {
                $("#last_event").html(response);
            }
        });

        document.getElementById('last_event').innerHTML = "Last Event: " + type;

        if(type == 'pick') {
            document.getElementById("pick").disabled = true;
            document.getElementById("throw_success").disabled = false;
            document.getElementById("throw_fail").disabled = false;
            document.getElementById("place").disabled = false;
            document.getElementById("drop").disabled = false;
        } else {
            document.getElementById("pick").disabled = false;
            document.getElementById("throw_success").disabled = true;
            document.getElementById("throw_fail").disabled = true;
            document.getElementById("place").disabled = true;
            document.getElementById("drop").disabled = true;
        }
    });



</script>

</html>
