
/**
 * Defines a circular path
 * 
 */
Raphael.fn.circlePath = function(x, y, r) {      
  	var s = "M" + x + "," + (y-r) + "A"+r+","+r+",0,1,1,"+(x-0.1)+","+(y-r)+" z";   
  	return s; 
};