/* Custom Admin Script Codes */
(function ($) {
    let forminptextarea =  document.querySelectorAll( 'textarea' );
	console.log('swvcasv');
    forminptextarea.forEach((item) => {
        if (item.className === 'codemirror_text') {
			console.log('swvcasv');
            var codeMirror = CodeMirror.fromTextArea( item, {
				'autofocus': true,
				'extraKeys': {
					'Tab': 'autocomplete'
				},
                'mode': 'text/css',
                'lineNumbers': true,
                'lineWrapping': true,
				'theme': 'material',
            });
            codeMirror.focus();
        }
    })    
})(jQuery);