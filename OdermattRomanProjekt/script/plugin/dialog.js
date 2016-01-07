

(function($){
	
	var createLabel = function($controll, options){
		var $label = $('<label for="' + options.id + '">' + options.label + '</label>');
		var $div = $('<div></div>').append($label);
		$div.append($controll);
		$controll = $div;
		return $controll;
	};
	var createMessage = function(options){
		var $controll = $('<p>'+ options.text + '</p>');
		if(options.addClass !== undefined){
			$controll.addClass(options.addClass);
		}
		return $controll;
	};
	
	var createInput = function(options){
		var $div = $('<div></div>')
		if(options.addClass !== undefined){
			$div.addClass(options.addClass);
		}
		if(options.label !== undefined){
			$div = $div.append($('<label for="' + options.id + '">' + options.label + '</label>'));
		}
		
		var $controll = $('<input type="'+ options.type + '" id="' + options.id + '" />');
		if(options.name !== undefined){
			$controll.attr('name', options.name);
		}
		if(options.value !== undefined){
			$controll.attr('value', options.value);
		}
		
		$div = $div.append($controll);
		
		
		return $div;
	};
	
	var createTextArea = function(options){
		
		var $div = $('<div></div>')
		if(options.addClass !== undefined){
			$div.addClass(options.addClass);
		}
		if(options.label !== undefined){
			$div = $div.append($('<label for="' + options.id + '">' + options.label + '</label>'));
		}
		
		var $controll = $('<textarea type="'+ options.type + '" id="' + options.id + '" />');
		if(options.name !== undefined){
			$controll.attr('name', options.name);
		}
		if(options.value !== undefined){
			$controll.html(options.value);
		}
		
		$div = $div.append($controll);
		
		
		return $div;
	};
	
	var createButton = function(options){
		var $button;
		if(options.id !== undefined){
			$button = $('<button type="button" id="' + options.id + '" ' + options.html +' >' + options.label + '</button>');
		}else{
			$button = $('<button type="button" value="' + options.label + '" ' + options.html +' >' + options.label + '</button>');
		}
		$button.click(options.click);
		if(options.addClass !== undefined){
			$button.addClass(options.addClass);
		}
		return $button;
	};
	
	var defaulDialogOps = {
			theme: 'dialog',
			divID: 'popUp',
			controlls: [
			            {'type': 'message', 'text': 'example of a message', 'addClass':'bigTitel'},
			            {'type': 'text', 'label': 'TextInput', 'id':'myInput1'},
			            {'type': 'button', 'label': 'Buttone', 'id':'myButton2', 'addClass':'red right', 'click': print}
			            ]
	};
	
	$.fn.makeDialog = function(_ops){
		_ops = $.extend({}, defaulDialogOps, _ops);
		var $dialog = $('<div id="' + _ops.divID + '"></div>');
		$dialog.addClass(_ops.theme);
		var tmpControll = {};
		for (var i = 0; i < _ops.controlls.length; i++) {
			tmpControll = _ops.controlls[i];
			switch(tmpControll.type){
				case('message'):
					$dialog.append(createMessage(tmpControll));
					break;
				case('button'):
					$dialog.append(createButton(tmpControll));
					break;
				case('html'):
					$dialog.append($(tmpControll.html));
					break;
				case ('textarea'):
					$dialog.append($(createTextArea(tmpControll)));
					break;
				default:
					$dialog.append(createInput(tmpControll));
					break;
			}
		};
		this.append($dialog);
	};
	
	var defaultFormOps = {
			theme: 'form',
			formID: 'newForm',
			action: '',
			methode: 'GET',
			controlls: []
	};
	$.fn.makeForm = function(_ops){
		_ops = $.extend({}, defaultFormOps, _ops);
		var $form = $('<div id="' +_ops.formID + '"/>');
		$form.addClass(_ops.theme);
		var tmpControll = {};
		for (var i = 0; i < _ops.controlls.length; i++) {
			tmpControll = _ops.controlls[i];
			switch(tmpControll.type){
				case('message'):
					$form.append(createMessage(tmpControll));
					break;
				case('button'):
					$form.append(createButton(tmpControll));
					break;
				case('html'):
					$form.append($(tmpControll.html));
					break;
				case ('textarea'):
					$form.append($(createTextArea(tmpControll)));
					break;
				default:
					$form.append(createInput(tmpControll));
					break;
			}
		};
		this.append($form);
	};
}(jQuery));