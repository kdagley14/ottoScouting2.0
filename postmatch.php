<!DOCTYPE html>
<html>
<head>
    <title>Otto Scouting</title>
    <link rel="stylesheet" href="css/materialize.css">
</head>
<body>
    <div class="container center-align">
        <div class="section">
            <h5>Did the robot try to climb?</h5>
            <button class="btn" id="climb_yes">Yes</button>
            <button class="btn" id="climb_no">No</button>
        </div>

        <div id="climbs" style="display:none;">
            <div class="divider"></div>
            <div class="section">
                <div class="section">
                    <h5>What type of climb?</h5>
                    <button class="btn" id="self">Climbed by Itself</button>
                    <button class="btn" id="hang">Hung on Another Bot</button>
                    <button class="btn" id="lifted">Picked Up by/Used Ramp of Another Bot </button>
                </div>
                <div class="section">
                    <h5>Was the robot successful?</h5>
                    <button class="btn" id="climb_success">Success</button>
                    <button class="btn" id="climb_fail">Failure</button>
                </div>
                <div class="section">
                    <h5>Did they lift any other robots?</h5>
                    <button class="btn" id="lifted_0">No</button>
                    <button class="btn" id="lifted_1">Lifted 1 Robot</button>
                    <button class="btn" id="lifted_2">Lifted 2 Robots</button>
                </div>
                <div class="section">
                    <h5 id="climb_error" style="display:none;color:red;">Please select all of the fields.</h5>
                    <button class="btn orange" id="save_climb">Save</button>
                </div>
            </div>
        </div>

        <div id="platform" style="display:none;">
            <div class="divider"></div>
            <div class="section">
                <h5>Did the robot successfully end on the platform?</h5>
                <button class="btn" id="platform_yes">Yes</button>
                <button class="btn" id="platform_no">No</button>
            </div>
        </div>

        <div id="matchNotes">
            <div class="divider"></div>
            <div class="section">
                <h5>Have Any Notes?</h5>
                <h6>(ex. foul explainations, good defense, bad driving, good strategy, etc.)</h6>
                <br>
                <div class="input-field col s12">
                    <textarea id="notes" class="materialize-textarea"></textarea>
                    <label for="notes">Match Notes</label>
                </div>
            </div>
            <div class="section">
                <button id="saveNotes" class="btn orange" style="display:none;">Save Notes</button>
            </div>
        </div>

        <!--Hidden Values--->
        <div>
            <form action="index.php" method="post">
                <?php
                   foreach($_POST as $key=>$value) { ?>
                       <input id="<?php echo $key?>" type="hidden" name="<?php echo $key?>" value="<?php echo $value?>"/>
                <?php } ?>
                <button id="newMatch" class="btn green" type="submit" style="display:none;">New Match</button>
            </form>

            <input id="climb_type" type="hidden">
            <input id="climb_status" type="hidden">
            <input id="climb_lifted" type="hidden">
        </div>

    </div>



</body>

<script src="jquery-3.3.1.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
<script src="js/postmatch.js"></script>

</html>
