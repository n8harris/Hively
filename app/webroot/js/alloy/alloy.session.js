/**
 *  alloy.api.js - Part of the Alloy Library
 *
 *  Copyright (c) 2012, Tyler Seymour <tyler@unwitty.com>
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 *  following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *  disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
 *  disclaimer in the documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 *  INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 *  WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 *  USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  @require alloy.js
 *  @require alloy.api.js
 */

/*global $ alloy */


alloy.Session = function (data) {

	/**
	 * The user's session id
	 * @property	{string}
	 * @protected
	 */
	this._sessionId = null;

	/**
	 * Whether or not user is logged in
	 * @property	{bool}
	 * @protected
	 */
	this._loggedIn	= false;

	/**
	 * User's email address
	 * @property	{string}
	 * @protected
	 */
	this._email		= null;

	/**
	 * User's last login date
	 * @property	{string}
	 * @protected
	 */
	this._lastLoginDate		= null;

	/**
	 * User's role
	 * @property	string
	 * @protected
	 */
	this._role	= "anon";

	/**
	 * Whether or not the user has administrative rights
	 * @property	{bool}
	 * @protected
	 * TODO: Decouple from public library
	 */
	this._isAdmin	= false;

	if(data) {
		this.swallowApiCall(data);
	}
};
alloy.makeSingleton(alloy.Session);

/**
 * Triggered on successful login/logout.
 */
alloy.Session.EVENT_SESSION_CHANGE	= 'alloy_event_session_change';

/**
 * Triggered on login failure
 */
alloy.Session.EVENT_LOGIN_FAILURE	= 'alloy_event_login_failure';

/**
 * Triggered on logout failure
 */
alloy.Session.EVENT_LOGOUT_FAILURE	= 'alloy_event_logout_failure';

/**
 * Processes a response from the backend API, updates session information
 * @param {!object} response
 */
alloy.Session.prototype.swallowApiCall = function (response) {

	if (response.session_id && response.role) {

		this._sessionId = response.session_id;
		this._email		= response.email;
		this._role		= response.role;
		this._lastLoginDate = this._lastLoginDate ? this._lastLoginDate : response.last_login_date;
		this._loggedIn	= false;
		this._isAdmin	= false;

		switch(this._role) {
			case 'admin':
				this._isAdmin = true;
				this._loggedIn = true;
				break;
			case 'user':
				this._loggedIn = true;
				break;
			case 'business':
				this._loggedIn = true;
				break;
		}
	}

};

/**
 * Attempt to log log the user in.
 *
 * @param {!string} email
 * @param {!string} password
 */
alloy.Session.prototype.login = function (email, password) {

	var success = function (response) {
		this._loggedIn = true;
		$(window.document).trigger(alloy.Session.EVENT_SESSION_CHANGE, true);
	};
	var error = function (response) {
		$(window.document).trigger(alloy.Session.EVENT_LOGIN_FAILURE);
	};

	alloy.Api.getInstance().request({
		api			: "user",
		call		: "login",
		data		: {
			email		: email,
			password	: password
		},
		success		: $.proxy(success,	this),
		error		: $.proxy(error,	this)
	});
};

/**
 * Attempt to log the user out
 */
alloy.Session.prototype.logout = function () {

	var success = function (event) {
		this._loggedIn	= false;
		this._role		= 'anon';
		$(window.document).trigger(alloy.Session.EVENT_SESSION_CHANGE, false);
	};
	var error = function (event) {
		$(window.document).trigger(alloy.Session.EVENT_LOGOUT_FAILURE);
	};

	alloy.Api.getInstance().request({
		api		: 'user',
		call	: 'logout',
		data	: {},
		success : $.proxy(success, this),
		error	: $.proxy(error, this)
	});
};

alloy.Session.prototype.setSessionId = function (sessionId) {
	this._sessionId = sessionId;
};

alloy.Session.prototype.getSessionId = function () {
	return this._sessionId;
};

alloy.Session.prototype.isLoggedIn = function () {
	return this._loggedIn;
};

alloy.Session.prototype.getSessionId = function () {
	return this._sessionId;
};

alloy.Session.prototype.isAdmin = function () {
	return this._isAdmin;
};

alloy.Session.prototype.getEmail = function () {
	return this._email;
};
