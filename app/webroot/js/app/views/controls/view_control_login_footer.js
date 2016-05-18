App.views.ControlLoginFooter = alloy.View.extend({

	id: "globalFooter",

	_initialize: function(options) {
			$(this.el).html($(App.tmpl.tmpl_login_footer_control).render({
				register: App.register
			}));
	}
});
