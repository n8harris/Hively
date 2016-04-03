App.Router = App.RouterBase.extend({

	routes: {
		"": "businesses"
	},
	public: [
		"businesses"
	],
	require_active_account: [],

	businesses: function(opt_params) {
			var view = new App.views.PageBusinesses();
			this.showView(view);
	}
});
alloy.makeSingleton(App.Router);
