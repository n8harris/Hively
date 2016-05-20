App.views.PageViewProfile = alloy.View.extend({

	events: {},

	_initialize: function(options){
		this.profileContainer = '.profile-container';
		this.id = options.hasOwnProperty('id') ? options.id : null;
		this.role = App.Data.getInstance().user.get('role');
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_profile;
		$(this.el).html($(tmpl).render());
		this.injectProfileCard();
	},

	injectProfileCard: function() {
		if (this.role == 'business'){
			alloy.Api.getInstance().request({
				api: 'user',
				call: 'get',
				data: {
					user_id: this.id
				},
				success: this.onGetUser,
				error: this.onGetUserError
			});
		} else {
			alloy.Api.getInstance().request({
				api: 'business',
				call: 'get',
				data: {
					account_id: this.id
				},
				success: this.onGetBusiness,
				error: this.onGetBusinessError
			});
		}

	},
	onGetUser: function(response){
		if(response.status > 0){
			var user = response.data.user;
			$(this.profileContainer, this.el).append($(App.tmpl.tmpl_partial_profile_card).render({
				business: false,
				first_name: user.first_name,
				last_name: user.last_name,
				profile_pic: user.profile_pic_url,
				bio: user.bio
			}));
			App.vent.trigger('rendered');
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}
	},
	onGetUserError: function(response){
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
	},
	onGetBusiness: function(response){
		if(response.status > 0){
			var business = response.data.business;
			$(this.profileContainer, this.el).append($(App.tmpl.tmpl_partial_profile_card).render({
				business: true,
				name: business.name,
				line1: business.address_line_1,
				city: business.city,
				state: business.state,
				zip: business.zip,
				profile_pic: business.profile_pic_url
			}));
			App.vent.trigger('rendered');
		} else {
			var alloyApi = alloy.Api.getInstance();
			alloyApi._requests.splice(0,1);
			bootbox.alert(response.message);
		}
	},
	onGetBusinessError: function(response){
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
	}
});
