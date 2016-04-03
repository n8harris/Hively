/**
 *  alloy.js - Part of the Alloy Library
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
 */

var alloy = {};

alloy.global = this;

alloy.tmpl = alloy.tmpl || {};
alloy.views = alloy.views || {};
alloy.events = alloy.events || {};


alloy.nullFunction = function() {};

alloy.abstractFunction = function() {
	throw new Error("Abstract function missing implementation.");
};

alloy.makeSingleton = function(ClassName) {
	ClassName.setInstance = function() {
		return ClassName.__instance = new ClassName(arguments[0],arguments[1],arguments[2],arguments[3],arguments[4],arguments[5]);
	};
	ClassName.getInstance = function() {
		return ClassName.__instance || ClassName.setInstance.apply(this, arguments);
	};
	ClassName.deleteInstance = function() {
		ClassName.__instance = null;
	};
};

alloy.inheritFrom = function(parent, child) {
	function Constructor() {}

	Constructor.prototype = parent.prototype;
	child.superClass_ = parent.prototype;
	child.prototype = new Constructor();
	child.prototype.constructor = child;
};

alloy.isDefined = function(value) {
	return value !== undefined;
};

alloy.isNull = function(value) {
	return value === null;
};

alloy.isDefindedAndNotNull = function(value) {
	return value != null;
};

/**
 * Takes an array and builds a dictionary, with each key taken from the specified field in the record
 * @param {!array} ary
 * @param {!string} field
 * @return {object}
 */

alloy.buildIndexByField = function(ary, field) {

	var data = {};
	for(var i = 0; i < ary.length; i++) {
		data[ary[i][field]] = ary[i];
	}
	return data;
};

/**
 * Take a simple array of values and creates a dictionary, where each value is also the key
 * @param {!array} ary
 */
alloy.buildIndexFromValue = function(ary) {

	var data = {};
	for(var i = 0; i < ary.length; i++) {
		data[ary[i]] = ary[i];
	}
	return data;
};