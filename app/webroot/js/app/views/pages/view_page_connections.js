App.views.PageConnections = alloy.View.extend({

	events: {},

	_render: function() {
		$(this.el).html($(App.tmpl.tmpl_page_connections).render());
		App.vent.trigger('rendered');
	}
});
