
App.views.ControlHeader = alloy.View.extend({

	id: "headerTop",

	events: {
    "click .hamburger": "onMenuClick",
		"click .logout": "onLogoutClick"
	},

	_initialize: function(options) {
			var Data = App.Data.getInstance();

			this.user = Data.user;
			$(this.el).html($(App.tmpl.tmpl_header_control).render({
				user: this.user.toJSON()
			}));
	},

  onMenuClick: function(){
    if(!$('.dropdown-menu-h').hasClass('active')){
      $('.dropdown-menu-h').show().addClass('active');
    } else {
      $('.dropdown-menu-h').hide().removeClass('active');
    }
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
