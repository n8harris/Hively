
App.views.PageBusinesses = alloy.View.extend({

	className: 'container',
	events: {},

	_initialize: function(options) {
		this.businessesContainer = "#businesses"
	},

	_render: function() {
		$(this.el).html($(App.tmpl.tmpl_page_businesses).render());

		alloy.Api.getInstance().request({
			api: 'business',
			call: 'get',
			success: this.onBusinesses
		});
	},

	onBusinesses: function(response) {

		var businesses = new App.Collection(response.data.businesses);
		businesses.forEach(this.injectBusiness);
	},

	injectBusiness: function(business) {

		$(this.businessesContainer, this.el).append($(App.tmpl.tmpl_partial_business).render({
			business: business.toJSON()
		}));
	},
});
