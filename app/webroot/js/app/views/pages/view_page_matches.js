
App.views.PageMatches = alloy.View.extend({

	events: {},

	_initialize: function(options) {
		this.matchesContainer = '.match-container';
		this.headingClass = 'match-yellow';
		this.matchId = null;
		this.matches = null;
		this.role = App.Data.getInstance().user.get('role');
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_matches;
		$(this.el).html($(tmpl).render());

		alloy.Api.getInstance().request({
			api: 'match',
			call: 'list_all',
			data: {},
			success: this.onMatches,
			error: this.onMatchesError
		});
	},

	onMatches: function(response) {

		if(response.status > 0) {
			var matches = response.data;
			if (this.role == 'user') {
				matches.businesses.forEach(function(business, index, array){
					this.injectBusiness(business);
					if (index === array.length - 1) {
             App.vent.trigger('rendered');
         	}
				}, this);
			} else {
				matches.users.forEach(function(user, index, array){
					this.injectUser(user);
					if (index === array.length - 1) {
             App.vent.trigger('rendered');
         	}
				}, this);
			}
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}

	},
	onMatchesError: function(response){
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
	},
	injectBusiness: function(business){
		if (business.percentage > 50) {
			this.headingClass = 'match-blue';
		} else {
			this.headingClass = 'match-yellow';
		}
		$(this.matchesContainer).html($(App.tmpl.tmpl_partial_profile_card).render({
			business: true,
			name: business.name,
			line1: business.address_line_1,
			city: business.city,
			state: business.state,
			zip: business.zip,
			percentage: business.percentage,
			match_id: business.match_id,
			heading_class: this.headingClass,
			match: true,
			profile_pic: business.profile_pic_url
		}));
	},
	injectUser: function(user){
		if (user.percentage > 50) {
			this.headingClass = 'match-blue';
		} else {
			this.headingClass = 'match-yellow';
		}
		$(this.matchesContainer).html($(App.tmpl.tmpl_partial_profile_card).render({
			business: false,
			first_name: user.first_name,
			last_name: user.last_name,
			email: user.email,
			bio: user.bio,
			percentage: user.percentage,
			match_id: user.match_id,
			heading_class: this.headingClass,
			match: true,
			profile_pic: user.profile_pic_url
		}));
	}
});
