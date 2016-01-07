define('script/page/login',['jquery', 'myDialog'], function($){
	return function () {
		var lsettings = {};
		
		var submit_Login = function(){
		  if($('#username').val().trim().length < 3){
		    alert('Spielername muss mindestens 3 Zeichen beinhalten.');
		  }else{
		    $.ajax({
				'url': lsettings.submitUrl,
				'type': 'POST',
				'data': {'passwd': $('#passwd').val().trim(), 'username': $('#username').val()},
				'dataType': 'json'
			}).done(function(_data){
				if(_data.success){
					window.location.href = _data.uri;
				}else{
				  alert("Falsches Passwort!");
				}
			}); 
		  }
		};
		var that = {
				init: function(_s){
					lsettings = _s;
					$('.main').addClass('shadow');
					var ops = {controlls: [
					                 {'type' : 'text', 'label': 'Benutzername', 'id': 'username'},
					                 {'type' : 'password', 'label': 'Passwort', 'id': 'passwd'},
					                 {'type': 'button', 'label': 'Anmelden', 'addClass': 'red', 'id': 'btnGo', 'click': submit_Login},
					                 {'type': 'message', 'text': 'Zum Spielen: Spielername eingeben und Passwort leer lassen.'}
					           ]};
					$('.main').makeDialog(ops);
				},
				mainreplace: function(_replaceContent, _animation){
					$('.main').replaceWith(_replaceContent);
				}
		};
		return that;
	}();
});