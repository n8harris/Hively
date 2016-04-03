alloy.tmpl.alert = ([
	"<a class='close' data-dismiss='alert' href='#'>&times;</a>",
	"{{if title}}<h5>${title}</h5>{{/if}}",
	"{{if message}}<span>${message}</span>{{/if}}",
	"{{if html}}<div id='html'></div>{{/if}}"
]).join("");

$.templates({
	"alloy.tmpl.alert": alloy.tmpl.alert
});
