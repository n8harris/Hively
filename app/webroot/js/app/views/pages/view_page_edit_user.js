
App.views.PageEditUser = alloy.View.extend({

	events: {
		"submit form": "onSubmitForm",
	},

	_initialize: function() {
		this.user = App.Data.getInstance().user;
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_edit_user;
		$(this.el).html($(tmpl).render({
			bio: this.user.get('bio')
		}));
		App.vent.trigger('rendered');
	},

	onSubmitForm: function(event) {
		event.preventDefault();
		var $form = $(event.target);
		var formValues = $form.serializeArray().reduce(function(a, x) { a[x.name] = x.value; return a; }, {});

		if(this.validateForm()) {

			$(".hively-button", this.el).val('Editing');
			var data = {
				bio: formValues.bio
			};

			alloy.Api.getInstance().request({
				api: 'user',
				call: 'edit',
				data: data,
				success: this.onEditSuccess,
				error: this.onEditError
			});
		}
	},
	onEditSuccess: function(response) {

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
			$(".hively-button", this.el).val('Edit');
		}
	},
	onEditError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
		if (response.hasOwnProperty('message')) {
			bootbox.alert(response.message);
		}
		$(".hively-button", this.el).val('Edit');
	},
	validateForm: function() {

		return true;
	}
});
