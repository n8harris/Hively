/**
 *  alloy.view.js - Part of the Alloy Library
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
alloy.View = Backbone.View.extend({

	initialize: function(options) {

		_.bindAll(this);
		this._initialize(options);

		this._isRendered = false
		this._isBound = false;
		this._inDom = false;
	},
	_initialize: function(options) {},

	inject: function(element, options) {

		options = options ? options : {};
		options = _.defaults(options, {
			method: 'html'
		});

		if(!this._isRendered) {
			this.render();
		}

		if(!this._isBound) {
			this.bind();
		}


		$(element)[options.method](this.el);

//		if(opt_replace) {
//			$(element).replaceWith(this.el);
//		} else {
//			$(element).html(this.el);
//		}

		if(!this._inDom) {
			this.onEnterDom();
		}
		return this;
	},
	injectChild: function(child, element, options) {

		child.inject(element, options);

		this.addChild(child);
		return this;
	},

	render: function() {
		if(this._isRendered) {
			this.unbind();
		}
		this._render();
		this.bind();
		this._isRendered = true;
		$('input, textarea', this.el).placeholder();
		return this;
	},
	_render: function() {},
	addChild: function(childView) {
		if(this._children == undefined) {
			this._children = [];
		}
		this._children.push(childView);
		return this;
	},
	clearChildren: function() {

		this.unbindChildren();
		this._children = [];
		return this;
	},
	bind: function() {

		if(this._isBound) {
			return this;
		}

		if(this._isRendered) {
			// Backbone's view will delegate events upon initialization
			// we only need to delegate events if we're re-rendering
			this.delegateEvents();
		}

		this.bindChildren();
		this._bind();

		this._isBound = true;
		return this;
	},
	_bind: function() {},
	unbind: function() {
		this.stopListening();
		this.undelegateEvents();
		this.unbindChildren();
		this._unbind();
		this._isBound = false;
		return this;
	},
	_unbind: function() {},
	bindChildren: function() {
		if(this._children) {
			for(var i = 0; i < this._children.length; i++) {
				this._children[i].bind();
			}
		}
		return this;
	},
	unbindChildren: function() {
		if(this._children) {
			for(var i = 0; i < this._children.length; i++) {
				this._children[i].unbind();
			}
		}
		return this;
	},
	onEnterDom: function() {

		this._inDom = true;
		this._onEnterDom();
		if(this._children) {
			for(var i = 0; i < this._children.length; i++) {
				this._children[i].onEnterDom();
			}
		}
		return this;
	},
	_onEnterDom: function() {},
	onExitDom: function() {

		this._inDom = false;
		this._onExitDom();
		if(this._children) {
			for(var i = 0; i < this._children.length; i++) {
				this._children[i].onExitDom();
			}
		}
		return this;
	},
	_onExitDom: function() {}



});