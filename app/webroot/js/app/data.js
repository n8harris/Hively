
App.Data = function(data) {

	_.bindAll(this);

	data = data || {};


	data = _.defaults(data, {
		session: new App.Model(),
		user: new App.Model(),
		account: new App.Model(),
		users: new App.Collection(),
		clubhouseSubscription: new App.Model(),
		completedTasks: new App.Collection(),
		progress: new App.Collection(),
		chapterActivities: new App.Collection()
	});


	_.extend(this, data);
	
	// make sure the root user and the one included in the full user list are the same model
	if(this.user.get('id')) {
		var user = this.users.get(this.user.get('id'));
		if(user) {
			this.user = user;
		}
	}
};
alloy.makeSingleton(App.Data);

App.Data.prototype.isLoggedIn = function() {

	return !(this.user.get('role') == 'anon' || this.user.get('role') == undefined);
};


