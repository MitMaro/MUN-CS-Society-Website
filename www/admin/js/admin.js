/*------------------------------------------------------------------------------
    File: www/admin/js/admin.js
 Project: MUN Computer Science Society Website
 Version: 0.1.0
      By: Tim Oram
 Website: http://society.cs.mun.ca
   Email: muncssociety@gmail.com
------------------------------------------------------------------------------*/

var Admin = {
	setup: function(){
	
		// validators
		Admin.validators = {}
		
		// dom
		Admin.dom = {section: {}};
		Admin.dom.navigation = $('nav');
		Admin.dom.content = $('bd');
		Admin.dom.login = $('logout');
		Admin.dom.login.addEvent('click', Admin.logout);
		Admin.dom.login_loader = $('login_loader');
		Admin.dom.sections = $$('div.section');
		Admin.dom.sections.each(Admin.section_setup);
		Admin.dom.zebra_tables = $$('table.zebraify');
		Admin.dom.zebra_tables.each(Admin.zerbaify);
		Admin.equalize_navigation();
		Admin.dom.forms = $$('.ajaxify');
		Admin.dom.forms.each(function(form){Admin.setup_ajax_form_with_validation(form)});
	},
	
	section_setup: function(el){
		// basic element setup
		el.dom = {
			body: el.getElement('div.body'),
			menu: el.getElement('div.menu'),
			add: el.getElement('div.body div.add'),
			edit: el.getElement('div.body div.edit'),
			list: el.getElement('div.body div.list'),
			equal: el.getElements('.left, .right')
		};
		
		// equalize some heights
		Admin.equalize_height(el.dom.equal);
		
		// setup the accordion for the selection
		el.accordion = new Accordion(el.dom.body, [], [], {
			initialDisplayFx: false,
			display:2,
			//fixedHeight: Math.max(el.dom.add.getSize().y, el.dom.edit.getSize().y, el.dom.list.getSize().y),
			onComplete: Admin.equalize_navigation,
			transition:"sine:in",
			duration:"short"
		});
		
		// setup menu if found
		if(el.dom.menu !== null) {
			el.dom.menu.show();
			el.dom.menu.dom = {
				add: el.dom.menu.getElement('div.add')
			}
		}

		if(el.dom.list !== null) {
			el.accordion.addSection(new Element('a'), el.dom.list);
		}
		
		// setup add if found
		if(el.dom.add !== null) {
			//el.dom.add.hide();
			el.dom.add.dom = {
				form: el.dom.add.getElement('form'),
				close: el.dom.add.getElement('div.close')
			}
			el.accordion.addSection(new Element('a'), el.dom.add);
			if(el.dom.menu.dom.add !== null) {
				el.dom.menu.dom.add.addEvent('click', function(){el.accordion.display(el.dom.add)})
			}
		}

		// setup edit if found
		if(el.dom.edit !== null) {
			//el.dom.edit.hide();
			el.dom.edit.dom = {
				form: el.dom.edit.getElement('form'),
				close: el.dom.edit.getElement('div.close')
			}
			el.accordion.addSection(new Element('a'), el.dom.edit);
			if(el.dom.menu.dom.add !== null) {
				// display(0) will always show the default list
				el.dom.menu.dom.add.addEvent('click', function(){el.accordion.display(0)})
			}

		}
		el.accordion.display(0);
	},
	
	setup_ajax_form_with_validation: function(form, success, request, failure){
		form.dom = {}
		
		// dom
		form.dom.loader = $pick(form.getElements('.loader'), new Element('div'));
		form.dom.submit = $pick(form.getElements('.submit'), new Element('div'));
		form.dom.success = $pick(form.getElements('.success'), new Element('div'));
		form.dom.error = $pick(form.getElements('.error'), new Element('div'));
		
		// callbacks
		form.callbacks = {}
		form.callbacks.success = $pick(success, function(response){
			try {
				data = JSON.decode(response, true);
			}
			catch(err) {
				data = null;
			}
			this.dom.loader.hide();
			if(data == null) {
				Site.showError("Invalid JSON Data Returned");
			}
			else if(data.status == 200) {
				this.dom.success.show();
				setTimeout(function(){
					this.dom.success.hide();
					this.dom.submit.show();
				}.bind(this), 3000);
			}
			else {
				this.dom.submit.show();
				if(data.message != null) {
					this.dom.error.set("text", data.message);
				}
				else {
					this.dom.error.set("text", "Unknown Error");
				}
				this.dom.error.show();
			}
		}.bind(form));
		form.callbacks.request = $pick(request, function(){
			this.dom.loader.show();
			this.dom.submit.hide();
			this.dom.success.hide();
			this.dom.error.hide();
		}.bind(form));
		form.callbacks.failure = $pick(failure, function(){
			this.dom.loader.hide();
			this.dom.submit.show();
			Site.showError("An Unknown Error Has Occurred")
		}.bind(form))
		
		Admin.validators[form.name] = new FormCheck(form, {
			submitByAjax: true,
			onAjaxSuccess: form.callbacks.success,
			onAjaxRequest: form.callbacks.request,
			onAjaxFailure: form.callbacks.failure
		});
	},
	
	logout: function(e){
		e.preventDefault();
		Admin.dom.login_loader.removeClass("hidden");
		Admin.dom.login.addClass("hidden");
		new Request.JSON({
			method: 'get',
			url: '/action/admin/login/logout',
			onSuccess: function(data){
				if(data.status == 200) {
					Site.showSuccess("Logout Successful");
					setTimeout(function(){window.location.href = window.location.href;}, 1500);
				}
				else {
					Site.showError("Error Logging Out")
					setTimeout(function(){window.location.href = window.location.href;}, 4000);
				}
			}
			
		}).send();
	},
	
	equalize_height: function(els){
		var height = 0;
		els.each(function(el){
			if(el.getSize().y > height) {
				height = el.getSize().y;
			}
		});
		els.each(function(el){
			el.setStyle('height', height + 10);
		});
	},
	
	zerbaify: function(table){
		table = $pick(table.getElement('tbody'), table);
		
		table.getElements('tr').each(function(el, i){
			el.removeEvent('mouseenter', Site.tableHoverEnter);
			el.addEvent('mouseenter', Site.tableHoverEnter);
			el.removeEvent('mouseleave', Site.tableHoverExit);
			el.addEvent('mouseleave', Site.tableHoverExit);
			if ( i % 2 == 0 ) {
				el.removeClass("odd");
				el.addClass("even");
			}
			else {
				el.removeClass("even");
				el.addClass("odd");
			}
		});
		
	},

	equalize_navigation: function(){
		var height = 400;
		var height = Math.max(Admin.dom.content.getSize().y, 400);
		Admin.dom.navigation.tween('height', height);//setStyle("height", height);
	}
};

window.addEvent('domready', function(){	
	Admin.setup();
});
