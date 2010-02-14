/*------------------------------------------------------------------------------
    File: www/admin/js/login.js
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

var Page = {
	setup: function(){
		// DOM
		Page.form = $('login');
		Page.loader = $('loader');
		Page.action = $$('p.submit');
		Page.success = $('success');
		Page.error = $('error');
		Page.inputs = {
			username: $('username'),
			password: $('password')
		};

		Page.inputs.username.addClass("validate['required','alphanum','length[0,50]']");
		Page.inputs.password.addClass("validate['required','length[5,-1]']")
		Page.validators = {
			login: new FormCheck(Page.form, {
				submitByAjax: true,
				onAjaxSuccess: Page.ajax_success,
				onAjaxRequest: Page.ajax_request,
				onAjaxFailure: Page.ajax_error
			})
		};

	},
	
	ajax_error: function(){
		Page.login_button.show();
		Page.loader.hide();
		Site.showError("An Unknown Error Has Occured");
	},
	ajax_request: function(){
		Page.error.hide();
		Page.action.hide();
		Page.loader.show();
	},
	ajax_success: function(response){
		data = JSON.decode(response);
		if(data.status == 200) {
			if(data.data.logged_in) {
				Page.success.show();
				Page.form.hide();
				// refresh page to load the admin index page
				setTimeout(function(){ window.location.href = window.location.href},1000);
			}
			else {
				Page.error.show();
				Page.action.show();
			}
		}
		else {
			Page.action.show();
		}
		Page.loader.hide();
	}
};

window.addEvent('domready', function(){	
	Page.setup();
});
