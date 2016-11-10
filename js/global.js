/* Global js file - A file to include various JavaScript code */

// Sticky top menu
/*jQuery(function( $ ){
	$(window).scroll(function() {
		var yPos = ( $(window).scrollTop() );
		if(yPos > 200) { // show sticky menu after screen has scrolled down 200px from the top
			$("..site-header").fadeIn();
		} else {
			$("..site-header").fadeOut();
		}
	});
});
*/

// Adding code to create an effect on the header/nav on scroll
jQuery(function( $ ){

	if( $( document ).scrollTop() > 0 ){
		$( '.site-header' ).addClass( 'dark' );			
	}

	// Add opacity class to site header
	$( document ).on('scroll', function(){

		if ( $( document ).scrollTop() > 0 ){
			$( '.site-header' ).addClass( 'dark' );		
		} else {
			$( '.site-header' ).removeClass( 'dark' );			
		}

	});


});
