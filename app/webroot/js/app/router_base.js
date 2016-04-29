
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
		/*if(!App.Data.getInstance().isLoggedIn() && !this.isPublic(name)) {

		}

		if(!this.isPublic() && this.requiresActiveAccount(name) && !App.Data.getInstance().account.get('can_access')) {

		}*/

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

		window.location.reload();
	},
	showView: function(view) {

		if(this.currentView) {
			this.currentView.unbind();
		}
		this.currentView = view;

		$("#main").html($(App.tmpl.tmpl_partial_loader).render());
		view.inject($("#main"));
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
