App.views.ControlLoginHeader = alloy.View.extend({

	id: "headerTop",

	_initialize: function(options) {
			$(this.el).html($(App.tmpl.tmpl_login_header_control).render());
	}
});
