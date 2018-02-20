<!DOCTYPE html>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="jquery-3.3.1.min.js"></script>
</head>
<body>
    <form action="match.php" method="post">
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
        <p>Bot is starting with power cube: </p>

        <label class="switch">
            <input type="checkbox">
            <span class="slider round"></span>
        </label>
        <br>
        <input id="startMatch" type="submit" value="Start Match">

    </form>
</body>

<script>
    function showDiv() {
        document.getElementById('powerCubeDiv').style.display = "block";
    }
</script>

</html>
