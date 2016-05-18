
App.views.PageLanding = alloy.View.extend({

	events: {},

	_render: function() {
		$(App.header).hide();
		$(App.footer).hide();
		$(this.el).html($(App.tmpl.tmpl_page_landing).render());
		App.vent.trigger('rendered');
	}
});
