
App.views.ControlHeader = alloy.View.extend({

	id: "headerTop",

	events: {
    "click .hamburger-menu": "onMobileMenuClick",
		"click .logout": "onLogoutClick",
		"click #usernameLink": "onUsernameLinkClick",
		"click .mobile-menu": "onMobileMenuClick",
		"click .slide-out-overlay": "onMobileMenuClick",
		"click .close-nav": "onMobileMenuClick",
		"click .slide-out a": "onMobileMenuClick",
	},

	_initialize: function(options) {
			var Data = App.Data.getInstance();

			this.user = Data.user;
			$(this.el).html($(App.tmpl.tmpl_header_control).render({
				user: this.user.toJSON()
			}));
	},

	onMobileMenuClick: function(){
		var slideOut = $('.slide-out');
		if (slideOut.hasClass('active')){
			this.closeNav(slideOut);
		} else {
			this.openNav(slideOut);
		}
	},
	closeNav: function(el){
		el.removeClass('active');
		$('html').removeClass('menu-open');
	},
	openNav: function(el){
		el.addClass('active');
		$('html').addClass('menu-open');
	},
	onLogoutClick: function(event) {
		event.preventDefault();

		alloy.Api.getInstance().request({
			api: 'user',
			call: 'logout',
			data: {},
			success: this.onLogoutSuccess,
			error: this.onLogoutError
		});
	},
	onLogoutSuccess: function(response) {
		App.Router.getInstance().navigate("login");
		window.location.reload();
	},
	onLogoutError: function(response) {
		bootbox.alert(response.message);
	}
});
