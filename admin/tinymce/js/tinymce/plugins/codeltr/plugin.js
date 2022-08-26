/*
* ltr plugin
* v1.0
* By Ahmed Aboelsaoud
* Date 06/20/2013
*/
tinymce.PluginManager.add('codeltr', function(editor) {
	function changeDirection(){
		var selectData = editor.selection.getContent();
		editor.insertContent('<span dir="ltr">'+selectData+'</span>',{format: 'text'});
	}
	editor.addButton('codeltr', {
		icon: 'ltr',
		text: 'Code',
		tooltip: 'Code left to write',
		onclick: function() {
			changeDirection();
        }
	});
});