
App.views.PageChoosePath = alloy.View.extend({

	events: {},

	_render: function() {
		$(this.el).html($(App.tmpl.tmpl_page_choose_path).render());
		App.vent.trigger('rendered');
	}
});
