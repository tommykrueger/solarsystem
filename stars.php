<?php 

//Astronomic unit in mio. km
$AE = 149600000;

$objects = array(
	array(
		'type' => 'star', 
		'name' => 'Blue Giant', 
		'color' => '#005CFF', 
		'diameter' => 43920000, 
		'distance' => 0, 
		'orbitTime' => null
	),
	array(
		'type' => 'star', 
		'name' => 'Blue Dwarf', 
		'color' => '#B5E4FF', 
		'diameter' => 5132000, 
		'distance' => 0, 
		'orbitTime' => null
	),
	array(
		'type' => 'star', 
		'name' => 'Yellow Dwarf (sunlike)', 
		'color' => '#FFE128', 
		'diameter' => 1392000, 
		'distance' => 0, 
		'orbitTime' => null
	),
	array(
		'type' => 'star', 
		'name' => 'Red Dwarf', 
		'color' => '#FF765A', 
		'diameter' => 162984, 
		'distance' => 0, 
		'orbitTime' => null
	)	
);

				


/*
 * Calculate down the sizes of the solar objects to make them fit to the screen.
 * The smallest size should be between 1 - 100
 */


				
$smallestRadius = 0;
$currentRadius = 0;
$habitableCenterRadius = 0;

$firstStep = true;
$sizeFactor = 5;

foreach($objects as $object):
	if($firstStep)
	{
		$smallestRadius = $object['diameter'] / 2;
		$firstStep = false;
	}
		
	$currentRadius = $object['diameter'] / 2;
	
	if($currentRadius < $smallestRadius)
	{
		$smallestRadius = $currentRadius;
	}
endforeach;

while((int)$smallestRadius > 10)
{
	$smallestRadius /= 10;
	$sizeFactor *= 10;
}

$sunCenterX = 800;
$sunCenterY = 400;
$i = 0;
$xOffset = 10;
$screenOffset = 100;
$xDifferences = 0;
$radius = 0;
$firstStep = true;
			
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<title>Raphael SVG Solar System</title>
<style type="text/css">

	body { 
		margin: 0; 
		padding: 0; 
		font: 80% 'Helvetica', Arial;
		background: #000;
		}
	
	#controls {
		width: 400px;
		margin: 0 0 0 -200px; 
		left: 50%;
		top: 20px;
		position: absolute;
		text-align: center;
		z-index: 9999;
		}
		
	#text {
		position: absolute;
		left: 30px;
		top: 30px;
		z-index: 9999;
		}
		#text div {
			width: 200px;
			display: none;
			padding: 10px;
			position: absolute;
			background: #fff;
			-moz-opacity: 0.7;
			-webkit-opacity: 0.7;
			opacity: 0.7;
			
			-moz-border-radius: 5px;
			-webkitborder-radius: 5px;
			border-radius: 5px;
			
			border: 1px solid #e0e0e0;
			
			}
			#text div label {
				width: 90px;
				height: 15px;
				display: block;
				float: left;
				}
			#text div label.title {
				width: 100%;
				height: 15px;
				display: block;
				float: none;
				font-size: 1.2em;
				font-weight: bold;
				color: #990000;
				font-style: italic;
				}
			#text div span {
				width: 90px;
				height: 15px;
				clear: both;
				}

</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="js/raphael.js"></script>

<script type="text/javascript">


//percent from the radius
var zoomFactor = 90; 

var animationSpeed = 200;

var spaceObjects = {planets: []};

var mouseX = 0;
var mouseY = 0;


$(document).ready(function(){
	var paper = Raphael(0, 0, $(window).width(), $(window).height());

	var viewBoxWidth = paper.width;
	var viewBoxHeight = paper.height;
	var canvasID = "#paper";
	var startX,startY;
	var mousedown = false;
	var dX,dY;
	var oX = 0, oY = 0, oWidth = viewBoxWidth, oHeight = viewBoxHeight;
	var viewBox = paper.setViewBox(oX, oY, viewBoxWidth, viewBoxHeight);
	viewBox.X = oX;
	viewBox.Y = oY;


	$('svg').mousedown(function(e){
		if(paper.getElementByPoint(e.pageX, e.pageY) != null) {return;}
        mousedown = true;
        startX = e.pageX; 
        startY = e.pageY;  
	});

	$('svg').mousemove(function(e){
        if (mousedown == false) {return;}
        dX = startX - e.pageX;
        dY = startY - e.pageY;
        x = viewBoxWidth / paper.width; 
        y = viewBoxHeight / paper.height; 

        dX *= x; 
        dY *= y; 
        //alert(viewBoxWidth +" "+ paper.width );
        
        paper.setViewBox(viewBox.X + dX, viewBox.Y + dY, viewBoxWidth, viewBoxHeight);

    });

	$('svg').mouseup(function(e){
		if(mousedown == false) {return;}
        mousedown = false;
        viewBox.X += dX;
        viewBox.Y += dY;  
	});
	
	var habitableHidden = true;
	var habitable = paper.circle(<?php echo $sunCenterX;?>, <?php echo $sunCenterY;?>, <?php echo $habitableCenterRadius*2.8?>).attr({
		fill: 'r(.5,.5)#000000:00-#000000:40-#00ff00:60-#000', 
		stroke: 'none', 
		"opacityStops": "0-0.25-0.4-0.0"
		});

		habitable.hide();

		
	
    initializeSolarSytem(paper);

    $(document).mousemove(function(e){
       mouseX = e.pageX; 
       mouseY = e.pageY;
    });

    function handle(delta) {
        vBHo = viewBoxHeight;
        vBWo = viewBoxWidth;
        if (delta < 0) {
        viewBoxWidth *= 0.95;
        viewBoxHeight*= 0.95;
        }
        else {
        viewBoxWidth *= 1.05;
        viewBoxHeight *= 1.05;
        }
               
	    viewBox.X -= (viewBoxWidth - vBWo) / 2;
	    viewBox.Y -= (viewBoxHeight - vBHo) / 2;          
	    paper.setViewBox(viewBox.X,viewBox.Y,viewBoxWidth,viewBoxHeight);
    }

    /** Event handler for mouse wheel event.
     */
    function wheel(event){
            var delta = 0;
            if (!event) /* For IE. */
                    event = window.event;
            if (event.wheelDelta) { /* IE/Opera. */
                    delta = event.wheelDelta/120;
            } else if (event.detail) { /** Mozilla case. */
                    /** In Mozilla, sign of delta is different than in IE.
                     * Also, delta is multiple of 3.
                     */
                    delta = -event.detail/3;
            }
            /** If delta is nonzero, handle it.
             * Basically, delta is now positive if wheel was scrolled up,
             * and negative, if wheel was scrolled down.
             */
            if (delta)
                    handle(delta);
            /** Prevent default actions caused by mouse wheel.
             * That might be ugly, but we handle scrolls somehow
             * anyway, so don't bother here..
             */
            if (event.preventDefault)
                    event.preventDefault();
        event.returnValue = false;
    }


    if (window.addEventListener)
            window.addEventListener('DOMMouseScroll', wheel, false);
    
    window.onmousewheel = document.onmousewheel = wheel;

    $('#habitable').click(function(){
        if(habitableHidden) {
    		habitable.show();
    		habitableHidden = false;
        }
        else {
        	habitable.hide();
    		habitableHidden = true;
        }

    });
});


/**
 * Takes a Raphael object and shows the information for the space object (start / planet)
 */
function showObjectInfo(obj){
	obj.css({'left': mouseX, 'top': mouseY});
	obj.fadeIn(150);
};
function hideObjectInfo(obj){
	obj.delay(150).fadeOut(150);
};

function initializeSolarSytem(paper) {

	<?php foreach($objects as $object):?>

		<?php $radius = floor($object['diameter'] / 2 / $sizeFactor); ?>

			var circle = paper.circle(<?php echo $sunCenterX;?>, <?php echo $sunCenterY;?>, <?php echo $radius?>).attr({
				fill: 'r(0.5, 0.5)<?php echo $object['color']?>:0-<?php echo $object['color']?>:80-<?php echo $object['color']?>:100', 
				filter: 'url(#sunfilter)', stroke: '<?php echo $object['color']?>', 
				'stroke-width': 1}).glow({
			        width: 60,
			        fill: true,
			        opacity: 0.5,
			        offsetx: 0,
			        offsety: 0,
			        color: "<?php echo $object['color']?>"
			    });

			<?php $sunCenterX += $radius * 2;?>
		    
	<?php endforeach;?>
}

</script>
</head>
<body>

	<div id="controls">
		<button id="zoomin">Zoom in</button> 
		<button id="zoomout">Zoom out</button>
		
		<button id="habitable">Toggle Habitable Zone</button>
	</div>
	
	<div id="text">
	<?php 
	/**
 	 * We want to display the planets in HTML so we can acces it later
	 */	
	?>			
	<?php foreach($objects as $object):?>
		
		<div id="<?php echo $object['name']?>-text">
			
			<label class="title"><?php echo $object['name'];?></label>
			<br/>
			<label>Type</label>
			<span><?php echo $object['type'];?></span>
			<br/>
			<label>Diameter</label>
			<span><?php echo $object['diameter'];?> KM</span>
			<br/>
			<label>Orbit Time</label>
			<span><?php echo $object['orbitTime'];?> Days</span>
			
		</div>
		
	<?php endforeach;?>
	
	</div>
	
</body>
</html>
