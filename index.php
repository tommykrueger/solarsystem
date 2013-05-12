<?php 

//Astronomic unit in mio. km
$AE = 149600000;
$_YEAR = 365.256;

$solarobjects = array(
					array(
						'type' => 'star', 
						'name' => 'Sun', 
						'color' => '#ff0000', 
						'diameter' => 1392000, 
						'distance' => 0, 
						'orbitTime' => null
					),
					array(
						'type' => 'rock planet', 
						'name' => 'Mercury', 
						'color' => '#AF9074', 
						'diameter' => 4879.4, 
						'distance' => 0.3871,
						'orbitTime' => 87.969
					),
					array(
						'type' => 'rock planet', 
						'name' => 'Venus', 
						'color' => '#A7A39A', 
						'diameter' => 12103.6, 
						'distance' => 0.723,
						'orbitTime' => 224.701
					),
					array(
						'type' => 'rock planet', 
						'name' => 'Earth', 
						'color' => '#797DAD', 
						'diameter' => 12756.32, 
						'distance' => 1,
						'orbitTime' => 365.256,
						'habitableCenter' => true
					),
					array(
						'type' => 'rock planet', 
						'name' => 'Mars', 
						'color' => '#D28F5B', 
						'diameter' => 6792.4, 
						'distance' => 1.524,
						'orbitTime' => 686.980
					),					
					array(
						'type' => 'gas planet', 
						'name' => 'Jupiter', 
						'color' => '#9A9697', 
						'diameter' => 142984, 
						'distance' => 5.203,
						'orbitTime' => 4328.9 	//11,86 years
					),
					array(
						'type' => 'gas planet', 
						'name' => 'Saturn', 
						'color' => '#C4AD8E', 
						'diameter' => 120536, 
						'distance' => 9.582,
						'orbitTime' => 10751.805 	//29,457 years
					),
					array(
						'type' => 'ice planet', 
						'name' => 'Uranus', 
						'color' => '#C6ECED', 
						'diameter' => 51118, 
						'distance' => 19.201,
						'orbitTime' => 30664.015 	//84,011 years
					),
					array(
						'type' => 'ice planet', 
						'name' => 'Neptune', 
						'color' => '#75AEFB', 
						'diameter' => 49528, 
						'distance' => 30.047,
						'orbitTime' => 60148.35 	//164,79 years
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
$sizeFactor = 10;

foreach($solarobjects as $solarobject):
	if($firstStep)
	{
		$smallestRadius = $solarobject['diameter'] / 2;
		$firstStep = false;
	}
		
	$currentRadius = $solarobject['diameter'] / 2;
	
	if($currentRadius < $smallestRadius)
	{
		$smallestRadius = $currentRadius;
	}
	
	if(isset($solarobject['habitableCenter']) && $solarobject['habitableCenter'] == true)
		$habitableCenterRadius = ($AE * (float)$solarobject['distance'] / 1000000);
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
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="js/raphael.js"></script>

<script type="text/javascript">

Raphael.fn.circlePath = function(x, y, r) {      
  	var s = "M" + x + "," + (y-r) + "A"+r+","+r+",0,1,1,"+(x-0.1)+","+(y-r)+" z";   
  	return s; 
};

/*
 * Define some setting vaiables here
 */

var orbitColor = '#555';
var orbitStrokeWidth = 1;

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
        if (delta >= 0) {
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


    if(window.addEventListener)
            window.addEventListener('DOMMouseScroll', wheel, false);
    
    window.onmousewheel = document.onmousewheel = wheel;
    


    /*
     * Interaction functions
     */

	var habitableHidden = true; 
    $('a#toggle-habitable').click(function(e){
		e.preventDefault();
        if(habitableHidden) {
    		habitable.show();
    		habitableHidden = false;
        }
        else {
        	habitable.hide();
    		habitableHidden = true;
        }
    });


    magneticFieldsHidden = true;
    $('a#toggle-magnetic-fields').click(function(e){
    	e.preventDefault();
        if(magneticFieldsHidden) {
    		$('ellipse').each(function(){
				$(this).hide(250);
        	});
    		magneticFieldsHidden = false;
        }
        else {
        	$('ellipse').each(function(){
				$(this).show(250);
        	});
        	magneticFieldsHidden = true;
        }
    });

    $('a#zoomout').click(function(e){
    	e.preventDefault();
    	vBHo = viewBoxHeight;
        vBWo = viewBoxWidth;

        viewBoxWidth *= 1.05;
        viewBoxHeight *= 1.05;
                        
	    viewBox.X -= (viewBoxWidth - vBWo) / 2;
	    viewBox.Y -= (viewBoxHeight - vBHo) / 2;          
	    
	    paper.setViewBox(viewBox.X, viewBox.Y, viewBoxWidth, viewBoxHeight);
	});
	
	$('a#zoomin').click(function(e){		
		e.preventDefault();
		vBHo = viewBoxHeight;
        vBWo = viewBoxWidth;

        viewBoxWidth *= 0.95;
        viewBoxHeight*= 0.95;
        
	    viewBox.X -= (viewBoxWidth - vBWo) / 2;
	    viewBox.Y -= (viewBoxHeight - vBHo) / 2;          
	    
	    paper.setViewBox(viewBox.X, viewBox.Y, viewBoxWidth, viewBoxHeight);
	});	


	
});

/**
 * Functions to show the tooltip on object hover
 */
function showObjectInfo(obj){
	obj.css({'left': mouseX, 'top': mouseY});
	obj.fadeIn(150);
};
function hideObjectInfo(obj){
	obj.delay(150).fadeOut(150);
};

function initializeSolarSytem(paper) {

	<?php foreach($solarobjects as $solarobject):?>

		<?php $radius = floor($solarobject['diameter'] / 2 / $sizeFactor); ?>
	
		<?php if($firstStep): ?>
			<?php $firstStep = false;?>
			<?php $xDifferences = $radius;?>

			var circle = paper.circle(<?php echo $sunCenterX;?>, <?php echo $sunCenterY;?>, <?php echo $radius?>).attr({
				fill: 'r(0.5, 0.5)#FF0033:0-#FF9933:80-#FFFF33:100', 
				filter: 'url(#sunfilter)', stroke: 'orange', 
				'stroke-width': 1}).glow({
			        width: 60,
			        fill: true,
			        opacity: 0.5,
			        offsetx: 0,
			        offsety: 0,
			        color: "#FFFF33"
			    });

		<?php else: ?>

			<?php $xDifferences += $radius;?>
	
			<?php $distance = $AE * (float)$solarobject['distance'] / 1000000; ?>

			<?php $habitableCenterRadius += $xDifferences?>
			
			var planetOrbit = paper.circle(<?php echo $sunCenterX;?>, <?php echo $sunCenterY;?>, <?php echo $distance + $xDifferences + $xOffset?>).attr({
				fill: 'none', 
				stroke: orbitColor, 
				'stroke-width': orbitStrokeWidth
				});

			<?php 
				if($radius <= 0)
					$radius = 1;
			?>

			//magnetic field (simplified)
			
			var magneticField = paper.ellipse(<?php echo $distance + $xDifferences + $xOffset + $sunCenterX + $radius*10;?>, <?php echo $sunCenterY;?>, <?php echo $solarobject['diameter'] / 500 ?>, <?php echo $solarobject['diameter'] / 1000 ?>).attr({
				stroke: '#3399AA', 
				'stroke-width': 1,
				'class': 'magentic'
				});
			
			var planetObj = paper.circle(<?php echo $distance + $xDifferences + $xOffset + $sunCenterX;?>, <?php echo $sunCenterY;?>, <?php echo $radius?>).attr({
				fill: '<?php echo $solarobject['color']?>', 
				stroke: '#3399AA', 
				'stroke-width': 1
				});

			$(planetObj.node).attr({'id': '<?php echo $solarobject['name']?>', 'class': 'space-object'});
			

			/*
			 * Rotate the object around the sun's center point 360 degrees.
			 * The time is calculated by the
			 * 1 millisecond => 1000 days in real time
			 *
			 */
			var orbitTime = <?php echo (double)$solarobject['orbitTime']*1000.00?>;
			 
			var anim = Raphael.animation({transform: "r360,<?php echo $sunCenterX?>, <?php echo $sunCenterY;?>"}, orbitTime);
			planetObj.animate(anim.repeat(Infinity));

			var animMagenticField = Raphael.animation({transform: "r360,<?php echo $sunCenterX?>, <?php echo $sunCenterY;?>"}, orbitTime);
			magneticField.animate(animMagenticField.repeat(Infinity));
			
			planetObj.hover(
				function(){
					showObjectInfo($('#<?php echo $solarobject['name']?>-label'));
			  	},
			  	function(){
			  		hideObjectInfo($('#<?php echo $solarobject['name']?>-label')); 	
			  	}
			);
			
			<?php $xDifferences += $radius + $xOffset;?>
			
		<?php endif;?>
		<?php $i++;?>
	<?php endforeach;?>
}

</script>
</head>
<body>

	<div id="controls">
		<a id="zoomin" class="button button-small blue" href="#">+</a> 
		<a id="zoomout" class="button button-small blue" href="#">-</a>
		<a id="toggle-habitable" class="button blue" href="#">Toggle Habitable Zone</a>
		<a id="toggle-magnetic-fields" class="button blue" href="#">Toggle Magnetic Fields</a>
	</div>
	
	<div id="description">
		This is a very simple example of our solar system in 2D.
		<br><br/>
		It shows the relative dimensions of the sun and the planets.  
		The relative dimensions of the objects are real. The relative distances between the planets are also real. But the absolute dimensions and distances are
		of course not real. The data is taken from wikipedia (diameters and distances from the sun).
		
		THe blue ovals show the magnetic fields. Us the controls on the top to change the visualization.
	</div>
	
	<div id="labels">
	<?php 
	/**
 	 * We want to display the planets in HTML so we can access them later
	 */	
	?>			
	<?php foreach($solarobjects as $solarobject):?>
		
		<div id="<?php echo $solarobject['name']?>-label">
			
			<span class="title"><?php echo $solarobject['name'];?></span>
			
			<table>
				<tr>
					<td>Type:</td>
					<td><?php echo $solarobject['type'];?></td>
				</tr>
				<tr>
					<td>Diameter:</td>
					<td><?php echo $solarobject['diameter'];?> km</td>
				</tr>
				<tr>
					<td>Distance Sun:</td>
					<td><?php echo $solarobject['distance'] * $AE?> mio. km (<?php echo $solarobject['distance']?> times earth distance)</td>
				</tr>
				<tr>
					<td>Orbit Time:</td>
					<td><?php echo $solarobject['orbitTime'];?> days (<?php echo round(($solarobject['orbitTime'] / $_YEAR), 2)?> years)</td>
				</tr>
			</table>
			
		</div>
		
	<?php endforeach;?>
	
	</div>
	
</body>
</html>
