
App = {
	environment: null,
	models: {},
	collections: {},
	tmpl: {},
	events: {
		USER_DELETED: 'user_deleted',
		CONTENT_PROGRESS: 'content_progress'
	},
	loader: '.logo-loader',
	main_container: '#main',
	footer_container: '#globalFooter',
	login: false,
	questions: false,
	register: false,
	choose_path: false,
	questionList: null,
	views: {},
	controls: {},
	overlays: {},
	vent: _.extend({}, Backbone.Events)

};

App.Model = Backbone.Model.extend({});

App.Collection = Backbone.Collection.extend({
	model: App.Model
});
