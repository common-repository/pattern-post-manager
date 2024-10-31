(function() {

    tinymce.PluginManager.add('custom_button_script', function( editor, url ) {

		editor.addButton( 'ppm-button', {
		 	text: its_ppm_button_caption,
			type: 'listbox', 
 			name: 'template', 
			icon: false,
			onselect: function(e){
				editor.insertContent(this.value());
			},
			values: its_ppm_item_values
		});

	});
})();
