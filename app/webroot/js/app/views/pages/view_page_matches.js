
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
			if(matches.matches.length){
				if (this.role == 'user') {
					matches.businesses.forEach(function(business, index, array){
						this.injectBusiness(business);
						if (index === array.length - 1) {
							App.vent.trigger('rendered');
							this.listenTo(App.vent, 'loaded', function(){
								var thisPointer = this;
								$(this.matchesContainer).jTinder({
									 onDislike: function (item) {
										 thisPointer.removeMatch(item);
									 },
									 onLike: function (item) {
										 thisPointer.approveMatch(item);
									 },
									animationRevertSpeed: 200,
									animationSpeed: 400,
									threshold: 1
							 });
						 });
	         	}
					}, this);
				} else {
					matches.users.forEach(function(user, index, array){
						this.injectUser(user);
						if (index === array.length - 1) {
								App.vent.trigger('rendered');
								this.listenTo(App.vent, 'loaded', function(){
									var thisPointer = this;
									$(this.matchesContainer).jTinder({
										 onDislike: function (item) {
											 thisPointer.removeMatch(item);
										 },
										 onLike: function (item) {
											 thisPointer.approveMatch(item);
										 },
										animationRevertSpeed: 200,
										animationSpeed: 400,
										threshold: 1
								 });
							 });
	         	}
					}, this);
				}
			} else {
				App.vent.trigger('rendered');
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
		App.vent.trigger('rendered');
	},
	removeMatch: function(item) {
		var match_id = $(item).data('match');
		alloy.Api.getInstance().request({
			api: 'match',
			call: 'edit',
			data: {
				match: match_id,
				reviewed: "true",
				approved: "false"
			},
			success: this.onSwipe,
			error: this.onSwipeError
		});
	},
	approveMatch: function(item) {
		var match_id = $(item).data('match');
		alloy.Api.getInstance().request({
			api: 'match',
			call: 'edit',
			data: {
				match: match_id,
				reviewed: "true",
				approved: "true"
			},
			success: this.onSwipe,
			error: this.onSwipeError
		});
	},
	onSwipe: function(response){
		if(response.status < 0) {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}
	},
	onSwipeError: function(response){
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
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
		$(this.matchesContainer).append($(App.tmpl.tmpl_partial_profile_card).render({
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
		if (user.percentage > 75) {
			this.headingClass = 'match-blue';
		} else {
			this.headingClass = 'match-yellow';
		}
		$(this.matchesContainer).append($(App.tmpl.tmpl_partial_profile_card).render({
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
