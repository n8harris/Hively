
App.views.PageProfile = alloy.View.extend({

	events: {},

	_initialize: function(options){
		this.profileContainer = '.profile-container';
		this.user = App.Data.getInstance().user;
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_profile;
		$(this.el).html($(tmpl).render());
		this.injectProfileCard();
	},

	injectProfileCard: function() {
		$(this.profileContainer, this.el).append($(App.tmpl.tmpl_partial_profile_card).render({
			first_name: this.user.get('first_name'),
			last_name: this.user.get('last_name'),
			profile_pic: this.user.get('profile_pic_url')
		}));
		App.vent.trigger('rendered');
	}
});
