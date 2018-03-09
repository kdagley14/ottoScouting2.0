var teamNum = $('#team_num').val();
var matchNum = $('#match_num').val();
var scoutName = document.getElementById('scoutName').value;

// Did the robot try to climb?

$('#climb_yes').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#climb_no').removeClass("teal darken-3");
    $('#climbs').show();
    $('#platform').hide();
});

$('#climb_no').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#climb_yes').removeClass("teal darken-3");
    $('#climbs').hide();
    $('#platform').show();
});

// What type of climb?

$('#self').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#hang').removeClass("teal darken-3");
    $('#lifted').removeClass("teal darken-3");
    $('#climb_type').val("self_climb");
});

$('#hang').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#self').removeClass("teal darken-3");
    $('#lifted').removeClass("teal darken-3");
    $('#climb_type').val("hang_on_bot");
});

$('#lifted').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#hang').removeClass("teal darken-3");
    $('#self').removeClass("teal darken-3");
    $('#climb_type').val("lifted_by_bot");
});

//Was the robot successful?

$('#climb_success').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#climb_fail').removeClass("teal darken-3");
    $('#climb_status').val("yes");
});

$('#climb_fail').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#climb_success').removeClass("teal darken-3");
    $('#climb_status').val("no");
});

// Did they lift any other robots?

$('#lifted_0').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#lifted_1').removeClass("teal darken-3");
    $('#lifted_2').removeClass("teal darken-3");
    $('#climb_lifted').val("0");
});

$('#lifted_1').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#lifted_0').removeClass("teal darken-3");
    $('#lifted_2').removeClass("teal darken-3");
    $('#climb_lifted').val("1");
});

$('#lifted_2').click(function(e) {
    $(this).addClass("teal darken-3");
    $('#lifted_0').removeClass("teal darken-3");
    $('#lifted_1').removeClass("teal darken-3");
    $('#climb_lifted').val("2");
});

// Save climb

$('#save_climb').click(function(e) {
    var type = $('#climb_type').val();
    var status = $('#climb_status').val();
    var lift = $('#climb_lifted').val();

    if (type == "" || status == "" || lift == "") {
        $('#climb_error').show();
    } else {
        $.ajax({
            url: '/php/insertClimb.php',
            type: 'POST',
            dataType:'json',
            data: {
                teamNum: teamNum,
                matchNum: matchNum,
                type: type,
                success: status,
                botsLifted: lift,
                scoutName: scoutName
            },
            success: function(msg) {
                if (status == "no") {
                    $('#platform').show();
                } else {
                    $('#saveNotes').show();
                }
                $('#climbs').hide();
                $('#climb_yes').prop("disabled", true);
                $('#climb_no').prop("disabled", true);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });
    }
});

$('#platform_yes').click(function(e) {
    $.ajax({
        url: '/php/insertClimb.php',
        type: 'POST',
        dataType:'json',
        data: {
            teamNum: teamNum,
            matchNum: matchNum,
            type: "platform",
            success: "yes",
            botsLifted: "0",
            scoutName: scoutName
        },
        success: function(msg) {
            $('#platform_yes').prop("disabled", true);
            $('#platform_no').prop("disabled", true);
            $('#climb_yes').prop("disabled", true);
            $('#climb_no').prop("disabled", true);
            $('#saveNotes').show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
});

$('#platform_no').click(function(e) {
    $.ajax({
        url: '/php/insertClimb.php',
        type: 'POST',
        dataType:'json',
        data: {
            teamNum: teamNum,
            matchNum: matchNum,
            type: "platform",
            success: "no",
            botsLifted: "0",
            scoutName: scoutName
        },
        success: function(msg) {
            $('#platform_yes').prop("disabled", true);
            $('#platform_no').prop("disabled", true);
            $('#climb_yes').prop("disabled", true);
            $('#climb_no').prop("disabled", true);
            $('#saveNotes').show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
});

$('#saveNotes').click(function(e) {
    var notes = $('#notes').val();
    $.ajax({
        url: '/php/insertNotes.php',
        type: 'POST',
        dataType:'json',
        data: {
            teamNum: teamNum,
            matchNum: matchNum,
            notes: notes,
            scoutName: scoutName
        },
        success: function(msg) {
            $('#saveNotes').hide();
            $('#newMatch').show();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
});
