/***************/
/* S C R I P T */
/***************/
var teamNum = document.getElementById('team_num').value;
var matchNum = document.getElementById('match_num').value;
var scoutName = document.getElementById('scoutName').value;
$('#end_match').hide();
$('#scoutingTeam').text("You are scouting Team " + teamNum);

// Set up last event
var startEvent = document.getElementById('has_cube').value;
document.getElementById('last_pc_event').value = startEvent;
document.getElementById('last_event').innerHTML = "Last Event: " + startEvent;

// Disable action buttons on start
document.getElementById("pick").disabled = true;
document.getElementById("place").disabled = true;
document.getElementById("drop").disabled = true;
document.getElementById("foul").disabled = true;
document.getElementById("breakdown").disabled = true;
document.getElementById("defended").disabled = true;

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
    document.getElementById("place").disabled = true;
    document.getElementById("drop").disabled = true;

    document.getElementById("foul").disabled = true;
    document.getElementById("breakdown").disabled = true;
    document.getElementById("defended").disabled = true;
});

/*************/
/* T I M E R */
/*************/

var timer = new Timer();
timer.start();
timer.addEventListener('secondsUpdated', function (e) {
    $('#timer').text("Match Time: " + timer.getTimeValues().toString());
    $('#match_seconds').val(timer.getTotalTimeValues().seconds);
    if (timer.getTimeValues().seconds == 17) {
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
        } else if (type == "place") {
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
                y: y,
                scoutName: scoutName
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

$('#defended').on('click', function(e) {
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
        url: '/php/insertDefense.php',
        type: 'POST',
        dataType:'json',
        data: {
            teamNum: teamNum,
            matchNum: matchNum,
            position: pos,
            matchSeconds: time,
            auton: auton,
            x: x,
            y: y,
            scoutName: scoutName
        },
        success: function(data) {
            console.log(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
    document.getElementById('last_table').value = "defense";
    document.getElementById('last_event').innerHTML = "Last Event: defended";
});

/*************/
/* F O U L S */
/*************/

var fouls = {
    general: 'general',
    // g05: 'g05', // Robot overextended
    // g07: 'g07', // Bumpers fell off
    // g09: 'g09', // Launching a cube outside of the allowed zones
    // g14: 'g14', // Pinning for more than 5 seconds
    // g15: 'g15', // Robot camped in front of opponent's exchange zone
    // g16: 'g16', // Robot made contact with opponent in null territory
    // g18: 'g18', // Robot made contact with opponent in the platform zone
    // g22: 'g22', // Robot possessed more than one power cube at a time
    // human: 'human',
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
                y: y,
                scoutName: scoutName
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
                y: y,
                scoutName: scoutName
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
