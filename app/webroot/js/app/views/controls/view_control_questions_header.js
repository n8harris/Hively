App.views.ControlQuestionsHeader = alloy.View.extend({

	id: "headerTop",

	_initialize: function(options) {
			$(this.el).html($(App.tmpl.tmpl_questions_header_control).render());
	}
});
