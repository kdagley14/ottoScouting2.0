function showDiv() {
    document.getElementById('powerCubeDiv').style.display = "block";
}

$('#red').on('click', function() {
    $(this).addClass("teal darken-3");
    $('#blue').removeClass("teal darken-3");
    $('#alliance').val("red");
});

$('#blue').on('click', function() {
    $(this).addClass("teal darken-3");
    $('#red').removeClass("teal darken-3");
    $('#alliance').val("blue");
});

$('#team1').on('click', function() {
    $(this).addClass("teal darken-3");
    $('#team2').removeClass("teal darken-3");
    $('#team3').removeClass("teal darken-3");
    $('#teamSlot').val("1");
});

$('#team2').on('click', function() {
    $(this).addClass("teal darken-3");
    $('#team1').removeClass("teal darken-3");
    $('#team3').removeClass("teal darken-3");
    $('#teamSlot').val("2");
});

$('#team3').on('click', function() {
    $(this).addClass("teal darken-3");
    $('#team2').removeClass("teal darken-3");
    $('#team1').removeClass("teal darken-3");
    $('#teamSlot').val("3");
});

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
    var team = alliance + "_" + teamSlot;
    if (alliance == "" || teamSlot == "" || matchNum == "") {
        $('#error').html("Please fill out all the fields.");
    } else {
        document.getElementById('match_num').value = matchNum;
        $.ajax({
            url: 'php/getTeam.php',
            type: 'POST',
            dataType:'json',
            data: {
                matchNum: matchNum,
                team: team
            },
            success: function(response) {
                var element = "<input type='hidden' name='team_num' id='team_num' value='" + response[team] + "'>";
                $('#display_team').html(element);
                $('#scoutingTeam').text("You are scouting Team " + response[team]);
                var select = document.getElementById("display_scouts");
                $('#scoutName').val(select.options[select.selectedIndex].value);
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
    var scoutName = document.getElementById('scoutName').value;
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
            y: y,
            scoutName: scoutName
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
    $('#startPos').hide();
    $('#form').show();
});
