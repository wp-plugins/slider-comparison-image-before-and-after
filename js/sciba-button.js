(function() {
	tinymce.PluginManager.add('sciba_mce_button', function( editor, url ) {
		editor.addButton('sciba_mce_button', {
			text: '',
			title: 'Insrt sciba shortcode',
			icon: 'sciba',
			onclick: function() {
				editor.insertContent('[sciba leftsrc="URL image on the left" leftlabel="The label text to the left" rightsrc="URL image on the right" rightlabel="The label text to the right" mode="horizontal or vertical"]');
			}
		});
	});
})();