/*
* snippet plugin
* v1.1
* Written by Ahmed Aboelsaoud
* https://github.com/ahmedsaoud31/snippet
* Date 06/17/2013
* Update 07/04/2013
* Released under LGPL License.
*/
function runSnippet(){
	$(function(){
		var code,lang,settings;
		$("div.snippet").each(function(){
			goSnippet($(this));
		});
		function goSnippet(element){
			$(element).removeAttr('style');
			$(element).removeAttr('contenteditable');
			$(element).css({"text-align":"left"});
			code = $(element).find('.code').html().replace(/\<\!\-\-\{/g,"").replace(/\}\-\-\>/g,"");
			$(element).find('.code').remove();
			code = code.replace(/\{HTMLST\}/g,"&LT;").replace(/\{HTMLET\}/g,"&GT;");
			lang = $(element).find('.lang').html().replace(/\<\!\-\-\{/g,"").replace(/\}\-\-\>/g,"");
			$(element).find('.lang').remove();
			settings = JSON.parse($(element).find('.settings').html().replace(/\<\!\-\-\{/g,"").replace(/\}\-\-\>/g,""));
			$(element).find('.settings').remove();
			$(element).find('pre').html(code);
			$(element).find('pre').snippet(lang, settings);
		}
	});
}
runSnippet();