
App.RouterBase = Backbone.Router.extend({


	public: [],
	require_active_account: [],

	lastViewName: null,
	headerControl: null,

	isPublic: function(name) {
		return _.indexOf(this.public, name) >= 0;
	},
	requiresActiveAccount: function(name) {
		return _.indexOf(this.require_active_account, name) >= 0;
	},
	before: function(route, name, args) {

		// verify user can load the view
		if(!alloy.Session.getInstance().isLoggedIn() && !this.isPublic(name)) {
			App.onLogin = window.location.hash;

			this.navigate("login", {trigger: true});
			return false;
		}

		$('html, body').animate({
			scrollTop: 0
		}, 50);

		return true;
	},
	initialize: function(options) {

		this.currentView = null;

		$(window).bind(alloy.events.SESSION_TIMEOUT, null, $.proxy(this.onSessionTimeout, this));

		this.bind('route', this._pageView);
	},
	onSessionTimeout: function(event) {

		event.stopPropagation();

		this.navigate("login", {trigger: false});

		window.location.reload();
	},
	showView: function(view) {
		var header;
		var footer;
		if (App.login) {
			header = new App.views.ControlLoginHeader();
			footer = new App.views.ControlLoginFooter();
			App.login = false;
			App.register = false;
		} else if (App.questions) {
			header = new App.views.ControlQuestionsHeader();
			App.questions = false;
		} else if (App.choose_path) {
			header = new App.views.ControlLoginHeader();
			footer = new App.views.ControlFooter();
			App.choose_path = false;
		} else {
			$(App.footer_container).empty();
			header = new App.views.ControlHeader();
			footer = new App.views.ControlFooter();
		}

		if (footer) {
			footer.inject($("#globalFooter"), {method: "replaceWith"});
		}
		header.inject($("#headerTop"), {method: "replaceWith"});
		this.listenTo(App.vent, 'rendered', function(){
			$(App.loader).fadeOut(300, function(){
				$(App.main_container).fadeIn(500);
			});
		});
		if(this.currentView) {
			this.currentView.unbind();
		}
		this.currentView = view;
		$(App.main_container).hide();
		$(App.loader).show();
		view.inject($(App.main_container));
	},
	_extractParameters: function(route, fragment) {
		var result = route.exec(fragment).slice(1);
		result.unshift(this._deparam(result[result.length-1]));
		return result.slice(0,-1);
	},
	_deparam: function(paramString){
		var result = {};
		if( ! paramString){
			return result;
		}
		$.each(paramString.split('&'), function(index, value){
			if(value){
				var param = value.split('=');
				result[param[0]] = param[1];
			}
		});
		return result;
	},
	_pageView: function() {

		var path = Backbone.history.getFragment();
		var route = path.split('?')[0];
	}
});
