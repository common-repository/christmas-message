/** 
 * Copyright CSSJockey | http://www.cssjockey.com
 */
var $cj = jQuery.noConflict();
$cj(document).ready(function(){
	$cj('.fade').animate( { borderRightWidth:"1px" }, 1000).fadeOut(500);

	$cj(".cjcontent").hide(0);
	$cj("h1.cjhead").click(function(){
		var id = $cj(this).attr("id");
		$cj("."+id).slideToggle(100);
		$cj(this).toggleClass("cjminus");
	})


// Toggle Category Ids
	$cj(".cjcats").hide(0);
	$cj(".cjshowcatids").click(function(){
		$cj(this).parent().find(".cjcats").slideToggle();
                return false;
	})

// Toggle Page Ids
	$cj(".cjpages").hide(0);
	$cj(".cjshowpageids").click(function(){
            $cj(this).parent().find(".cjpages").slideToggle();
            return false;
	})

})