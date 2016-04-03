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
 *  @require alloy.session.js
 */

/*global alloy */

/**
 * The Alloy API library
 *
 * The API forces synchronization of requests to the backend API library.  This is useful as the client state may change
 * as each request as made.
 */
alloy.Api = function() {

	/**
	 * Serialized list of requests
	 * @protected
	 */
	this._requests = [];
};
alloy.makeSingleton(alloy.Api);

/**
 * Push a new request onto the request queue.
 * @param {!object} data - should contain the following keys
 *						data.api	{!string}	The API library to reference
 *						data.call	{!string}	The API call to make
 *						data.data	{object}	Any data to pass to the API call
 *						data.success {function} Called upon success (valid response code returned from API.
 *						data.error	 {function} Called upon error (invalid response code returned from API.
 */
alloy.Api.prototype.request = function(data) {

	this._requests.push(data);


	if(this._requests.length === 1) {
		this._makeNextCall();
	}
};

/**
 * Make the next API request
 * @param opt_event
 */
alloy.Api.prototype._makeNextCall = function(opt_event) {

	if(this._requests.length === 0) {
		return;
	}

	// grab the first request in the queue.  Note that this is not removed until the API call returns
	var request	= this._requests[0];

	var payload = request.data || {};
//	payload.data = request.data; //JSON.stringify(request.data);
	payload.api		= request.api;
	payload.call	= request.call;

	// Setting session id to be added as authentication header instead of post param.
	var authHeader = alloy.Session.getInstance().getSessionId();


	var success = function(response) {
		// each API returns a session ID. Update our local copy as it may have changed
		alloy.Session.getInstance().swallowApiCall(response);

		if(response.status == -1 && response.message == 'Not authorized') {
			$(window).trigger(alloy.events.SESSION_TIMEOUT);
			return;
		}

		if(response.status == -1){
			request.error(response);
			return;
		}
		// function making the API request may pass along optional success and error callbacks
		// note that the error one is executed in response to an error code from the API, not the
		// ajax call failing
		if(response.status && request.success) {
			request.success(response);
		}
		if(!response.status && request.error) {
			request.error(response);
		}

		// remove the front of the call queue and if needed, make the next call
		this._requests.shift();
		if(this._requests.length > 0) {
			this._makeNextCall();
		}
	};

	var error = function(event) {

		if(event.status != 200){

			var prevApi = this._requests[0].api;
			var prevCall = this._requests[0].call;
			if(prevApi != "logger"){
				var badRequestStr = " for request api:"+this._requests[0].api+" call: "+this._requests[0].call;
				this._requests.splice(0,1);

				alloy.Api.getInstance().request({
					api: 'logger',
					call: 'add',
					data: {
						message: "\""+event.status+" "+event.statusText+"\""+badRequestStr
					},
					success: function(response){console.log(response);},
					error: function(response){console.log(response);}
				});

				if(event.status != 0){
					bootbox.alert('An error occurred while making a request: '+event.status+' - '+event.statusText);
				}
			}
			this._requests.shift();
			if(this._requests.length > 0) {
				this._makeNextCall();
			}
		}

	};

	$.ajax("/api", {
		data	: payload,
		success	: $.proxy(success, this),
		error	: $.proxy(error, this),
		type    : 'POST',
		beforeSend : function(xhr) {
			xhr.setRequestHeader("Authorization", authHeader);
		}

	});
};
