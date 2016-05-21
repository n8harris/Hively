App.Router = App.RouterBase.extend({

	routes: {
		"": "login",
		"login": "login",
		"register": "register",
		"questions": "questions",
		"choose_path": "choose_path",
		"register_business": "register_business",
		"profile": "profile",
		"connections": "connections",
		"matches": "matches",
		"view_profile?:params": "view_profile",
		"edit_user": "edit_user"
	},
	public: [
		"login",
		"register",
		"choose_path",
		"register_business"
	],
	require_active_account: [
		"businesses",
		"questions",
		"profile",
		"connections",
		"matches",
		"view_profile",
		"edit_user"
	],

	login: function(opt_params) {
			App.login = true;
			if(App.Data.getInstance().isLoggedIn()) {
				App.login = false;
				this.navigate("login", {trigger: true});
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
	register_business: function(opt_params) {
			App.login = true;
			App.register = true;
			var view = new App.views.PageRegister({category: 'business'});
			this.showView(view);
	},
	questions: function(opt_params) {
		if(App.Data.getInstance().user.get('answered')){
			this.navigate("profile", {trigger: true});
		} else {
			App.questions = true;
			var view = new App.views.PageQuestions();
			this.showView(view);
		}
	},
	choose_path: function(opt_params) {
			App.choose_path = true;
			var view = new App.views.PageChoosePath();
			this.showView(view);
	},
	profile: function(opt_params) {
			var view = new App.views.PageProfile();
			this.showView(view);
	},
	connections: function(opt_params) {
			var view = new App.views.PageConnections();
			this.showView(view);
	},
	matches: function(opt_params) {
			var view = new App.views.PageMatches();
			this.showView(view);
	},
	view_profile: function(opt_params) {
			var view = new App.views.PageViewProfile(opt_params);
			this.showView(view);
	},
	edit_user: function(opt_params) {
			var view = new App.views.PageEditUser();
			this.showView(view);
	}
});
alloy.makeSingleton(App.Router);
