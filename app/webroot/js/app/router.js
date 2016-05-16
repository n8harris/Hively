App.Router = App.RouterBase.extend({

	routes: {
		"": "login",
		"login": "login",
		"businesses": "businesses",
		"register": "register",
		"questions": "questions"
	},
	public: [
		"login",
		"register"
	],
	require_active_account: [
		"businesses",
		"questions"
	],

	businesses: function(opt_params) {
			var view = new App.views.PageBusinesses();
			this.showView(view);
	},
	login: function(opt_params) {
			App.login = true;
			if(App.Data.getInstance().isLoggedIn()) {
				App.login = false;
				this.navigate("businesses", {trigger: true});
				return;
			}

			var view = new App.views.PageLogin();
			this.showView(view);
	},
	register: function(opt_params) {
			App.login = true;
			App.register = true;
			var view = new App.views.PageRegister();
			this.showView(view);
	},
	questions: function(opt_params) {
			App.questions = true;
			var view = new App.views.PageQuestions();
			this.showView(view);
	},
});
alloy.makeSingleton(App.Router);
