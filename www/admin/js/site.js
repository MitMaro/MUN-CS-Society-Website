/*------------------------------------------------------------------------------
    File: www/admin/js/site.js
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

var Site = {
	notimoo: new Notimoo({height: "auto"}),
	showError: function(msg) {
		Site.notimoo.show({
			message: msg,
			visibleTime: 10000,
			customClass: "notimoo_error"
		});
	},
	showMessage: function(msg) {
		Site.notimoo.show({
			message: msg,
			visibleTime: 3000,
			customClass: "notimoo_message"
		});
	},
	showSuccess: function(msg) {
		Site.notimoo.show({
			message: msg,
			visibleTime: 3000,
			customClass: "notimoo_success"
		});
	},
	tableHoverEnter: function(e){
		var el;
		if(e.tagName != "tr") {
			el = e.target.getParent("tr");
		}
		else {
			el = e.target;
		}
		el.addClass("over");
	},
	tableHoverExit: function(e){
		var el;
		if(e.tagName != "tr") {
			el = e.target.getParent("tr");
		}
		else {
			el = e.target;
		}
		el.removeClass("over");
	}
};
