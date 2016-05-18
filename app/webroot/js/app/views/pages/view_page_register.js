
App.views.PageRegister = alloy.View.extend({

	events: {
		"submit form": "onSubmitForm"
	},

	_initialize: function(options) {
		this.isBusiness = options ? true : false;
	},

	_render: function() {
		var tmpl = App.tmpl.tmpl_page_register;
		$(this.el).html($(tmpl).render({
			business: this.isBusiness
		}));
		if (this.isBusiness) {
			alloy.Api.getInstance().request({
				api: 'business',
				call: 'list_all',
				data: {},
				success: this.onGetBusinesses,
				error: this.onGetBusinessesError
			});
		} else {
			App.vent.trigger('rendered');
		}
	},

	onGetBusinesses: function(response) {
		if(response.status > 0) {
			var businesses = response.data.businesses;
			var businessData = [];
			businesses.forEach(function(business, index, array){
				businessData.push({
					id: business.Business.id,
					text: business.Business.name
				});
			}, this);
			$(".business-select").select2({
			  data: businessData,
				width: '100%'
			});
			App.vent.trigger('rendered');
		} else {

			$(".list-group", this.el).addClass('error');
			bootbox.alert(response.message);
		}
	},

	onGetBusinessesError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
	},

	onSubmitForm: function(event) {
		event.preventDefault();
		var $form = $(event.target);
		var formValues = $form.serializeArray().reduce(function(a, x) { a[x.name] = x.value; return a; }, {});

		if(this.validateForm()) {

			$(".hively-button", this.el).val('Creating');
			var data = {
				first_name: formValues.firstname,
				last_name: formValues.lastname,
				username: formValues.username,
				password: formValues.password,
				phone: formValues.phone,
				email: formValues.email,
				birthday: formValues.birthday
			};

			if (this.isBusiness) {
				data.business_id = $('.business-select').val();
				data.role = 'business';
			}

			alloy.Api.getInstance().request({
				api: 'user',
				call: 'create',
				data: data,
				success: this.onRegisterSuccess,
				error: this.onRegisterError
			});
		}
	},
	onRegisterSuccess: function(response) {

		if(response.status > 0) {

			var redirect = null;

			if(response.data.redirect) {

				redirect = response.data.redirect;

			} else {
				App.Router.getInstance().navigate('questions', {trigger: false});
				redirect = 'questions';
			}

			// bootstrap the application again
			App.Router.getInstance().navigate(redirect, {trigger: false});
			window.location.reload();

		} else {

			$(".list-group", this.el).addClass('error');
			bootbox.alert(response.message);
			$(".hively-button", this.el).val('Create');
		}
	},
	onRegisterError: function(response) {
		var alloyApi = alloy.Api.getInstance();
		alloyApi._requests.splice(0,1);
		$(".hively-button", this.el).val('Create');
	},
	validateForm: function() {

		return true;
	}
});
