// asdf vars are timeouts for the selected location
	$scope.asdf = false;
	// make a timeout that does nothing to avoid undefined errors later in code
	var asdfTime = setTimeout(function(){},1);

// $apply X and Y selected location
function saveXY(x,y) {
    $scope.$apply(function() {
        $scope.xLoc = x;
        $scope.yLoc = y;
    });
}

// $apply the state of asdf
function asdfFunc(b){
    $scope.$apply(function() {
        $scope.asdf = b;
    });
}

// Initialize the canvas
function init() {
    // init vars
    var bc = document.getElementById("field-back-" + fieldSize);
    var b = bc.getContext('2d');
    b.globalAlpha = '1';

    // Make canvas listen for clicks and do a thing when clickied.
    var element = document.getElementById("field-top-" + fieldSize);
    element.addEventListener('mousedown', function(event) {
        // Init variables when canvas be clicked
        var canvas = document.getElementById("field-top-" + fieldSize);
        var context = canvas.getContext('2d');
        var rect = canvas.getBoundingClientRect();

        var xTemp = (event.clientX - rect.left);
        var yTemp = (event.clientY - rect.top);
        var clickX = (event.clientX - rect.left);
        var clickY = (event.clientY - rect.top);
        var xPos = parseInt(xTemp);
        var yPos = parseInt(yTemp);

        console.info('robot ' + xPos + ' ' + yPos);
        // Save position using the $apply function
        saveXY(xPos, yPos);

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

        // asdf toggler
        asdfFunc(true);
        clearTimeout(asdfTime);
        asdfTime = setTimeout(function(){asdfFunc(false);context.clearRect(0, 0, canvas.width, canvas.height);}, 30000);

        // General function to run after the user clicks on the field
        afterLocation(xPos, yPos);
    });
}

// General function to run after the user clicks on the field
function afterLocation(x, y) {
    $scope.$apply(function() {
        $scope.highPlace = false;
        $scope.highThrow = false;
        $scope.highThrowFail = false;
        $scope.ownership = false;
    });
}
