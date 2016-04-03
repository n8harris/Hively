/**
 *  alloy.overlay_manager.js - Part of the Alloy Library
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

alloy.OverlayManager = alloy.OverlayManager || {};

alloy.OverlayManager = function(opt_data) {

	this._overlays = [];

	$(window.document).bind(alloy.OverlayManager.EVENT_CLOSE, $.proxy(this._onOverlayClose, this));
//	$(document).bind('keyup', $.proxy(this.onKeyUp, this));
};
alloy.makeSingleton(alloy.OverlayManager);
alloy.OverlayManager.EVENT_CLOSE	= "alloy_event_overlay_close";


alloy.OverlayManager.prototype.add = function(view) {

	this._overlays.push(view);
	if(this._overlays.length === 1) {
		this.show(this._overlays[0]);
	}
};

alloy.OverlayManager.prototype.show = function(view) {

	var modalSize = view.modalSize ? view.modalSize : 'large';

	view.render();
	$(view.el).addClass('modal modal-' + view.modalSize);
//	$(view.el).on('hidden.Overlay', $.proxy(this._onOverlayClose, this));
	$(view.el).appendTo($("body")).modal({
		show: true,
		backdrop:true,
		keyboard: false
	});
	view.onEnterDom();
	$("body").addClass('modal-open');
};

alloy.OverlayManager.prototype._onOverlayClose = function(event, overlay) {

	this._overlays[0].unbind();
	$(this._overlays[0].el).modal('hide');
//	$("body").removeClass('modal-open');
	this._overlays.shift();
	if(this._overlays.length > 0) {
		this.show(this._overlays[0]);
	}
};

alloy.OverlayManager.prototype.isOpen = function() {
	return this._overlays.length > 0;
}

//alloy.OverlayManager.prototype.getCurrentOverlay = function() {
//	return this._overlays.length > 0 ? this._overlays[0] : null;
//};

//alloy.OverlayManager.prototype.closeCurrentAndClearAll = function() {
//
//	if(this._overlays.length > 0) {
//		var current = this._overlays.shift();
//		this._overlays = [];
//		current.el.remove();
//	}
//};

alloy.OverlayManager.prototype.closeCurrent = function() {

	if(this._overlays.length > 0) {
		$("body").removeClass('modal-open');
		this._overlays[0].unbind();
		$(this._overlays[0].el).modal('hide');
		this._overlays.shift();
		if(this._overlays.length > 0) {
			this.show(this._overlays[0]);
		}
		$(window.document).trigger(alloy.events.OVERLAY_CLOSED);
	}
};

//alloy.OverlayManager.prototype.onKeyUp = function(event) {
//	if(event.keyCode == 27 && this._overlays.length > 0) {
//		this.closeCurrent();
//	}
//};
