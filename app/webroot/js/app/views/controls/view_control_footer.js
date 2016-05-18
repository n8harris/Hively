App.views.ControlFooter = alloy.View.extend({

	id: "globalFooter",

	_initialize: function(options) {
			$(this.el).html($(App.tmpl.tmpl_footer_control).render());
	}
});
