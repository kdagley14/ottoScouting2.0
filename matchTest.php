<!DOCTYPE html>
<script src="jquery-3.3.1.js"></script>
<script src="easytimer/dist/easytimer.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
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
                <button id="g05" class="btn foul">G05: Robot overextended</button>
            </div>
            <div class="row center-align">
                <button id="g07" class="btn foul">G07: Bumpers fell off</button>
                <button id="g09" class="btn foul">G09: Launching a cube outside of allowed zones</button>
            </div>
            <div class="row center-align">
                <button id="g14" class="btn foul">G14: Pinning for 5+ seconds</button>
                <button id="g15" class="btn foul">G15: Camped in front of opponent's exchange zone</button>
            </div>
            <div class="row center-align">
                <button id="g16" class="btn foul">G16: Contact w/ opponent in null territory</button>
                <button id="g18" class="btn foul">G18: Contact w/ opponent in platform zone</button>
            </div>
            <div class="row center-align">
                <button id="g22" class="btn foul">G22: Had more than 1 power cube at a time</button>
                <button id="human" class="btn foul">Human Did Something Wrong</button>
            </div>
            <div class="row center-align">
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
                <button id="platform_success" class="btn green climb">Climbed Using Platform: Success</button>
                <button id="platform_fail" class="btn red climb">Climbed Using Platform: Failure</button>
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
            <form>
                <?php
                   foreach($_POST as $key=>$value) { ?>
                       <input id="<?php echo $key?>" type="hidden" name="<?php echo $key?>" value="<?php echo $value?>"/>
                <?php } ?>
                <button id="end_match" class="btn orange" formaction="index.php" onclick="return confirm('Are you sure you want to end the match?');">End Match</button>
            </form>
        </div>
        <div class="col s6 right-align">
            <button id="climb" data-target="climbPopup" class="btn modal-trigger reset">Climb</button>
            <button id="foul" data-target="foulPopup" class="btn orange modal-trigger reset">Foul</button>
            <button id="breakdown" data-target="breakdownPopup" class="btn red modal-trigger reset">Breakdown</button>
            <!--<button id="undo" class="btn">Undo Last Event</button>--->
        </div>
    </div>

    <div class="row center-align">
        <h5>Defended:</h5>
        <button id="oppTeam1Btn" class="btn green darken-2 reset">oppTeam1</button>
        <button id="oppTeam2Btn" class="btn green darken-2 reset">oppTeam2</button>
        <button id="oppTeam3Btn" class="btn green darken-2 reset">oppTeam3</button>
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
                    document.getElementById("oppTeam1Btn").disabled = false;
                    document.getElementById("oppTeam2Btn").disabled = false;
                    document.getElementById("oppTeam3Btn").disabled = false;
                    document.getElementById("climb").disabled = false;
                    document.getElementById("foul").disabled = false;
                    document.getElementById("breakdown").disabled = false;
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
        <button id="pick" class="btn reset">Pick</button>
        <button id="throw_success" class="btn reset" disabled>Throw Success</button>
        <button id="throw_fail" class="btn reset" disabled>Throw Fail</button>
        <button id="place" class="btn reset" disabled>Place</button>
        <button id="drop" class="btn reset" disabled>Drop</button>
    </div>
</div>
</body>

<script>

    /***************/
    /* S C R I P T */
    /***************/
    var teamNum = document.getElementById('team_num').value;
    var matchNum = document.getElementById('match_num').value;
    $('#end_match').hide();
    $('#scoutingTeam').text("You are scouting Team " + teamNum);

    // Load opponent teams on defense buttons
    $('#oppTeam1Btn').html(document.getElementById('oppTeam1').value);
    $('#oppTeam2Btn').html(document.getElementById('oppTeam2').value);
    $('#oppTeam3Btn').html(document.getElementById('oppTeam3').value);

    // Set up last event
    var startEvent = document.getElementById('has_cube').value;
    document.getElementById('last_pc_event').value = startEvent;
    document.getElementById('last_event').innerHTML = "Last Event: " + startEvent;

    // Disable action buttons on start
    document.getElementById("pick").disabled = true;
    document.getElementById("throw_success").disabled = true;
    document.getElementById("throw_fail").disabled = true;
    document.getElementById("place").disabled = true;
    document.getElementById("drop").disabled = true;
    document.getElementById("oppTeam1Btn").disabled = true;
    document.getElementById("oppTeam2Btn").disabled = true;
    document.getElementById("oppTeam3Btn").disabled = true;
    document.getElementById("climb").disabled = true;
    document.getElementById("foul").disabled = true;
    document.getElementById("breakdown").disabled = true;

    // Set modal actions
    $('.modal').modal({
        dismissable: true,
        opacity: .5
    });

    $('.foul').on('click', function(e) {
        $('#foulPopup').modal('close');
    });

    $('.climb').on('click', function(e) {
        $('#climbPopup').modal('close');
    });

    $('.breakdown').on('click', function(e) {
        $('#breakdownPopup').modal('close');
    });

    // Clear position on field and disable buttons after an action button is clicked
    $('.reset').on('click', function(e) {
        var canvas = document.getElementById("botPosition");
        var context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);

        document.getElementById("pick").disabled = true;
        document.getElementById("throw_success").disabled = true;
        document.getElementById("throw_fail").disabled = true;
        document.getElementById("place").disabled = true;
        document.getElementById("drop").disabled = true;

        document.getElementById("oppTeam1Btn").disabled = true;
        document.getElementById("oppTeam2Btn").disabled = true;
        document.getElementById("oppTeam3Btn").disabled = true;

        document.getElementById("climb").disabled = true;
        document.getElementById("foul").disabled = true;
        document.getElementById("breakdown").disabled = true;
    });

    /*************/
    /* T I M E R */
    /*************/

    var timer = new Timer();
    timer.start();
    timer.addEventListener('secondsUpdated', function (e) {
        $('#timer').text("Match Time: " + timer.getTimeValues().toString());
        $('#match_seconds').val(timer.getTotalTimeValues().seconds);
        if (timer.getTimeValues().seconds == 20) {
            $('#auton_done').hide();
            $('#auton').val('no');
            $('#end_match').show();
        }
    });


    $("#auton_done").on('click touchstart',function(e) {
        e.stopPropagation();
        e.preventDefault();
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
            e.preventDefault();
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            var x = document.getElementById('x').value;
            var y = document.getElementById('y').value;
            var typeWithZone = type;

            var alliance = document.getElementById('alliance').value;
            if (alliance == "blue") {
                x = 850 - x;
                y = 431 - y;
                pos = "POINT(" + x + " " + y + ")";
            }

            if (type == "pick") {
                if (x >= 0 && x <= 88 && y >= 110 && y<= 205) {
                    typeWithZone = "pick_exchange";
                } else if (x >= 255 && x <= 381 && y >= 102 && y<= 328) {
                    typeWithZone = "pick_pza";
                } else if (x >= 469 && x <= 595 && y >= 102 && y<= 328) {
                    typeWithZone = "pick_pzo";
                } else if (x >= 733 && x <= 850 && (y >= 0 && y<= 117 || y >= 314 && y<= 431)) {
                    typeWithZone = "pick_portal";
                } else if (x >= 135 && x <= 223 && y >= 168 && y<= 262) {
                    typeWithZone = "pick_pyramid";
                } else {
                    typeWithZone = "pick_floor";
                }
            } else if (type == "throw_success" || type == "throw_fail" || type == "place") {
                if (x > 536 && x <= 698 && y >= 0 && y<= 431) {
                    typeWithZone = type + "_switcho";
                } else if (x >= 153 && x < 316 && y >= 0 && y<= 431) {
                    typeWithZone = type + "_switcha";
                } else if (x >= 316 && x <= 536 && y >= 0 && y<= 431) {
                    typeWithZone = type + "_scale";
                } else if (x >= 0 && x <= 88 && y >= 110 && y<= 205) {
                    typeWithZone = type + "_exchange";
                }
            }

            $.ajax({
                url: 'php/insertPCEvent.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: typeWithZone,
                    position: pos,
                    matchSeconds: time,
                    auton: auton,
                    x: x,
                    y: y
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            document.getElementById('last_table').value = "pcEvents";
            document.getElementById('last_event').innerHTML = "Last Event: " + typeWithZone;
            document.getElementById('last_pc_event').value = typeWithZone;
        });
    }

    /*****************/
    /* D E F E N S E */
    /*****************/
    $('#oppTeam1Btn').on('click', function(e) {
        var pos = document.getElementById('position').value;
        var time = document.getElementById('match_seconds').value;
        var auton = document.getElementById('auton').value;
        var oppTeam = document.getElementById('oppTeam1').value;
        var x = document.getElementById('x').value;
        var y = document.getElementById('y').value;

        var alliance = document.getElementById('alliance').value;
        if (alliance == "blue") {
            x = 850 - x;
            y = 431 - y;
            pos = "POINT(" + x + " " + y + ")";
        }

        $.ajax({
            url: '/php/insertDefense.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                teamDefended: oppTeam,
                position: pos,
                matchSeconds: time,
                auton: auton,
                x: x,
                y: y
            },
            success: function(msg) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
        document.getElementById('last_table').value = "defense";
        document.getElementById('last_event').innerHTML = "Last Event: " + oppTeam + "_defend";
    });
    $('#oppTeam2Btn').on('click', function(e) {
        var pos = document.getElementById('position').value;
        var time = document.getElementById('match_seconds').value;
        var auton = document.getElementById('auton').value;
        var oppTeam = document.getElementById('oppTeam2').value;
        var x = document.getElementById('x').value;
        var y = document.getElementById('y').value;

        var alliance = document.getElementById('alliance').value;
        if (alliance == "blue") {
            x = 850 - x;
            y = 431 - y;
            pos = "POINT(" + x + " " + y + ")";
        }

        $.ajax({
            url: '/php/insertDefense.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                teamDefended: oppTeam,
                position: pos,
                matchSeconds: time,
                auton: auton,
                x: x,
                y: y
            },
            success: function(msg) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
        document.getElementById('last_table').value = "defense";
        document.getElementById('last_event').innerHTML = "Last Event: " + oppTeam + "_defend";
    });
    $('#oppTeam3Btn').on('click', function(e) {
        var pos = document.getElementById('position').value;
        var time = document.getElementById('match_seconds').value;
        var auton = document.getElementById('auton').value;
        var oppTeam = document.getElementById('oppTeam3').value;
        var x = document.getElementById('x').value;
        var y = document.getElementById('y').value;

        var alliance = document.getElementById('alliance').value;
        if (alliance == "blue") {
            x = 850 - x;
            y = 431 - y;
            pos = "POINT(" + x + " " + y + ")";
        }

        $.ajax({
            url: '/php/insertDefense.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                teamDefended: oppTeam,
                position: pos,
                matchSeconds: time,
                auton: auton,
                x: x,
                y: y
            },
            success: function(msg) {
                console.log(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
        document.getElementById('last_table').value = "defense";
        document.getElementById('last_event').innerHTML = "Last Event: " + oppTeam + "_defend";
    });

    /***************/
    /* C L I M B S */
    /***************/

    var climbs = {
        self_climb_success: 'self_climb_success',
        self_climb_fail: 'self_climb_fail',
        hang_climb_success: 'hang_climb_success',
        hang_climb_fail: 'hang_climb_fail',
        platform_success: 'platform_success',
        platform_fail: 'platform_fail'
    };

    for (let type in climbs) {
        $('#' + type).on('click', function(e) {
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            var x = document.getElementById('x').value;
            var y = document.getElementById('y').value;

            var alliance = document.getElementById('alliance').value;
            if (alliance == "blue") {
                x = 850 - x;
                y = 431 - y;
                pos = "POINT(" + x + " " + y + ")";
            }

            $.ajax({
                url: '/php/insertClimb.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: type,
                    position: pos,
                    matchSeconds: time,
                    auton: auton,
                    x: x,
                    y: y
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            document.getElementById('last_table').value = "climbs";
            document.getElementById('last_event').innerHTML = "Last Event: " + type;
        });
    }

    /*************/
    /* F O U L S */
    /*************/

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
            var x = document.getElementById('x').value;
            var y = document.getElementById('y').value;

            var alliance = document.getElementById('alliance').value;
            if (alliance == "blue") {
                x = 850 - x;
                y = 431 - y;
                pos = "POINT(" + x + " " + y + ")";
            }
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
                    auton: auton,
                    x: x,
                    y: y
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

    var breakdowns = {
        partial: 'partial',
        never_moved: 'never_moved',
        lost_parts: 'lost_parts',
        no_auto: 'no_auto',
        no_show: 'no_show',
        intermittent: 'intermittent',
        fell_over: 'fell_over'
    };

    for (let type in breakdowns) {
        $('#' + type).on('click', function(e) {
            var pos = document.getElementById('position').value;
            var time = document.getElementById('match_seconds').value;
            var auton = document.getElementById('auton').value;
            var x = document.getElementById('x').value;
            var y = document.getElementById('y').value;

            var alliance = document.getElementById('alliance').value;
            if (alliance == "blue") {
                x = 850 - x;
                y = 431 - y;
                pos = "POINT(" + x + " " + y + ")";
            }

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
                    auton: auton,
                    x: x,
                    y: y
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
