/* Custom Plugin Script Codes */
(function ($) {
    var product_maindiv  = document.querySelectorAll( '.woo_pro_img' );
    var product_img_2  = document.querySelectorAll( '.product_img_2' );    
    product_img_2.forEach( pro_img => { pro_img.style.display = 'none' });
    product_maindiv.forEach( div => {
        div.onmouseover = function(){
            setTimeout(function(){
                var img1 = div.querySelector('.product-img');
                var img2 = div.querySelector('.product_img_2');
                img1.style.display = 'none';
                img2.style.display = 'block';
            }, 0);
        }
        div.onmouseleave = function(){
            setTimeout(function(){
                var img1 = div.querySelector('.product-img');
                var img2 = div.querySelector('.product_img_2');
                img1.style.display = 'block';
                img2.style.display = 'none';
            }, 0);
        }
    } );
})(jQuery);

/* Sale countdown fucntion */
(function sale_countdown(){
	jQuery(".countdown").each(function() {
		var endtimer = jQuery(this).attr("data-sale");
		var countDownDate = new Date( endtimer ).getTime();
		var $this = jQuery(this);
		var x = setInterval(function() {
			var now = new Date().getTime();
			var distance = countDownDate - now;
			const days = Math.floor(distance / (1000 * 60 * 60 * 24));
			const hours = Math.floor((distance - days * (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			const minutes = Math.floor((distance - days * (1000 * 60 * 60 * 24) - hours * (1000 * 60 * 60)) / (1000 * 60));
			const seconds = Math.floor((distance - days * (1000 * 60 * 60 * 24) - hours * (1000 * 60 * 60) - minutes * (1000 * 60)) / 1000);
			$this.text( `${days} Days ${hours} Hours ${minutes} Minutes ${seconds} Seconds` );
			if (distance < 0) {
				clearInterval(x);
				$this.text('');
			}
			}, 1000);
	});	
};
sale_countdown();
)(jQuery);
