
App.views.ControlHeader = alloy.View.extend({

	id: "headerTop",

	events: {
    "click .hamburger": "onMenuClick"
	},

	_initialize: function(options) {
			$(this.el).html($(App.tmpl.tmpl_header_control).render());
	},

  onMenuClick: function(){
    if(!$('.dropdown-menu-h').hasClass('active')){
      $('.dropdown-menu-h').show().addClass('active');
    } else {
      $('.dropdown-menu-h').hide().removeClass('active');
    }
  }
});
