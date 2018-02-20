<!DOCTYPE html>
<script src="jquery-3.3.1.js"></script>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <form id="matchForm" action="postmatch.php" method="post">
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
                    });
                </script>
            </canvas>
        </div>

        <button id="foul">Foul</button>
        <button id="breakdown">Breakdown</button>

        <input id="team_num" type="hidden" name="team_num" value="1746">
        <input id="match_num" type="hidden" name="match_num" value="1">
        <input id="type" type="hidden" name="type">
        <input id="position" type="hidden" name="position" value="POINT(1 1)">
    </form>

    <button id="pick" onclick="insertEvent('pick')">Pick</button>
    <button id="throw_success" onclick="insertEvent('throw_success')">Throw Success</button>
    <button id="throw_fail" onclick="insertEvent('throw_fail')">Throw Fail</button>
    <button id="place" onclick="insertEvent('place')">Place</button>
    <button id="drop" onclick="insertEvent('drop')">Drop</button>
</body>

<script>
    function changeType(eventType) {
        document.getElementById('type').value = eventType;
    }

    function insertEvent(type) {
        var teamNum = document.getElementById('team_num').value;
        var matchNum = document.getElementById('match_num').value;
        var pos = document.getElementById('position').value;

        $('#' + type).on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/php/insertPCEvent.php',
                type: 'POST',
                dataType:'json',
                data: {
                    teamNum: teamNum,
                    matchNum: matchNum,
                    type: type,
                    position: pos
                },
                success: function(msg) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.responseText);
                }
            });
            return false;
        });
    }
</script>

</html>
