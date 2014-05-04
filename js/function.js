function debounce(fn, delay) {
  var timer = null;
  return function () {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      fn.apply(context, args);
    }, delay);
  };
}

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

function ValidUrl(str) {
  var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  if(!pattern.test(str)) {
    return false;
  } else {
    return true;
  }
}

(function($) {

	$(document).on('keypress', '#shareawall', function(e) {
		if ( e.keyCode == 0 || e.keyCode == 32 ) { //Check for space keypress
		
			var string = $(this).val(); //Get value of the textarea
			var pieces = string.split(' '); //Split the text into tokens
			
			jQuery.each( pieces, function( i, val ) { //Foreach token check if url
				
				//Is Valid?
				if( ValidUrl( val ) ){
					//If yes, get details
					
					var data = { action: 'link_preview_get_details', url: val };
				
					$.post(ajaxurl, data, function(response) {
						alert('Got this from the server: ' + response);
					});
	
					return; //Exist foreach, only first URL to take	
				}

			});
		}
	});

})(jQuery);