App.views.PageConnections = alloy.View.extend({

	events: {},

	_initialize: function() {
		this.role = App.Data.getInstance().user.get('role');
		this.connectionContainer = '.connections-container';
	},

	_render: function() {
		$(this.el).html($(App.tmpl.tmpl_page_connections).render());
		App.vent.trigger('rendered');

		alloy.Api.getInstance().request({
			api: 'match',
			call: 'list_all',
			data: {
				reviewed: "true",
				approved: "true"
			},
			success: this.onMatches,
			error: this.onMatchesError
		});
	},
	onMatches: function(response){
		if(response.status > 0) {
			var connections = response.data;
			if(connections.matches.length){
				if (this.role == 'user') {
					connections.businesses.forEach(function(business, index, array){
						this.injectBusiness(business);
						if (index === array.length - 1) {
							App.vent.trigger('rendered');
						}
					}, this);
				} else {
					connections.users.forEach(function(user, index, array){
						this.injectUser(user);
						if (index === array.length - 1) {
								App.vent.trigger('rendered');
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
	injectBusiness: function(business){
		$(this.connectionContainer).append($(App.tmpl.tmpl_partial_connection).render({
			business: true,
			name: business.name,
			id: business.account_id,
			profile_pic: business.profile_pic_url
		}));
	},
	injectUser: function(user){
		$(this.connectionContainer).append($(App.tmpl.tmpl_partial_connection).render({
			business: false,
			first_name: user.first_name,
			last_name: user.last_name,
			id: user.id,
			profile_pic: user.profile_pic_url
		}));
	}
});
