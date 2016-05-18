
App.views.PageLogin = alloy.View.extend({

	events: {
		"submit form": "onSubmitForm"
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_login;
		$(this.el).html($(tmpl).render());
		App.vent.trigger('rendered');
	},

	onSubmitForm: function(event) {
		event.preventDefault();
		var $form = $(event.target);
		var formValues = $form.serializeArray().reduce(function(a, x) { a[x.name] = x.value; return a; }, {});

		if(this.validateForm()) {

			$(".hively-button", this.el).val('Logging In');

			alloy.Api.getInstance().request({
				api: 'user',
				call: 'login',
				data: {
					username: formValues.username,
					password: formValues.password
				},
				success: this.onLoginSuccess,
				error: this.onLoginError
			});
		}
	},
	onLoginSuccess: function(response) {

		if(response.status > 0) {

			var redirect = null;

			if(response.data.redirect) {

				redirect = response.data.redirect;

			} else {
				App.Router.getInstance().navigate('profile', {trigger: false});
				redirect = 'profile';
			}

			// bootstrap the application again
			App.Router.getInstance().navigate(redirect, {trigger: false});
			window.location.reload();

		} else {

			$(".list-group", this.el).addClass('error');
			bootbox.alert(response.message);
			$(".hively-button", this.el).val('Login');
		}
	},
	onLoginError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
		$(".hively-button", this.el).val('Login');
		bootbox.alert(response.message);
	},
	validateForm: function() {

		return true;
	}
});
