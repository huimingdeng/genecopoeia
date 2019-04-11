var MyFAQs;
(function($) {
	var $document = $( document );
	MyFAQs = {
		init: function(){

		},
		faqToggle: function(obj){
			$(obj).siblings('ul').toggle(1000);
		}
	};
	$document.ready( function(){ MyFAQs.init(); } );
})(jQuery);