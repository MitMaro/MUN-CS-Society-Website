/*------------------------------------------------------------------------------
    File: www/admin/js/home.js
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

var Page = {
	setup: function(){
		Page.dom = {}
		Page.dom.forms = {
			update: $('action_update'),
			password: $('action_password')
		}
		
		Page.dom.forms.update.inputs = {
			name: $('name'),
			email: $('email')
		}
		
		Page.dom.forms.password.inputs = {
			password: $('password'),
			new_password: $('new_password'),
			confirm_password: $('confirm_password')
		}
		
		Page.dom.forms.update.inputs.name.addClass("validate['required']");
		Page.dom.forms.update.inputs.email.addClass("validate['required','email']");
		
		Page.dom.forms.password.inputs.password.addClass("validate['required','length[5,-1]']");
		Page.dom.forms.password.inputs.new_password.addClass("validate['required','length[5,-1]']");
		Page.dom.forms.password.inputs.confirm_password.addClass("validate['required','length[5,-1]','confirm[new_password]']");
		
		Admin.setup_ajax_form_with_validation(Page.dom.forms.update);
		Admin.setup_ajax_form_with_validation(Page.dom.forms.password);
	}
}


window.addEvent('domready', function(){	
	Page.setup();
});
