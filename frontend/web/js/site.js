function navChange() {
	if ($(window).scrollTop() >= 50) {
		$('.navbar').css('background-color','#f8f8f8');
		$('.navbar-brand').css('color', '#488214');
		$('.navbar-a').css('color', '#488214 !important');
		var input = $('#pitchsearch-keyword');
		input.css('color', '#5a5a5a');
	} else {
		$('.navbar').css('background-color','transparent');
		$('.navbar-brand').css('color', '#659D32');
		$('.navbar-a').css('color', '#659D32 !important');
		var input = $('#pitchsearch-keyword');
		input.css('color', '#9e9e9e');
	}	
}
$('document').ready(function() {
	navChange();
});

$(window).scroll(function () {
	navChange();
});

$('#slide-submenu').on('click',function() {		
	console.log("slide-submenu click");	        
    $(this).closest('.list-group').fadeOut('slide',function(){
    	$('.mini-submenu').fadeIn();	
    });
    
  });

$('.mini-submenu').on('click',function(){
	console.log("mini-submenu click");		
    $('#sidebar').toggle('slide');
    $('.mini-submenu').hide();
})
