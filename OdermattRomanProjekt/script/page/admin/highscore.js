define('script/page/admin/highscore', ['jquery', 'one'], function($, one){
	return function () {
		var settings;
		
		var $row;
		var deleteScoreCheck = function(){
		      $row = $(this).parent();
          var ctrls = [];
					ctrls.push({type : 'message',text: 'Beim Löschen werden die Statistiken der Fragen angepasst!'});
					ctrls.push({type : 'button',label : 'Abbrechen',id : 'myButton2',addClass : 'red right', click: closeDialog});
					ctrls.push({type : 'button',label : 'Fortfahren',id : 'myButton1',addClass : 'green right',click : deleteScore});
					$('#dialogShadow').removeClass('hidden');
					$('#dialogShadow').makeDialog({
					  controlls: ctrls
					})
		};
		var closeDialog = function(){
		  $('#dialogShadow').children().remove();
		  $('#dialogShadow').addClass('hidden');
		}
		var deleteScore = function(){
			$.ajax({
				type : "POST",
				url : settings.delScore,
				data : {
					id : $row.data('id')
				}
			}).done(function(data) {
				$row.remove();
				closeDialog();
				initTable($('#TableHighscore'));
			});
		};
		
		var initTable = function($table) {
			$table.addClass('Highscore');
			$.each($table.children('tbody').children('tr'), function(i,
					me) {
				if (i % 2 == 0) {
					$(me).addClass('Highscore mod1');
				} else {
					$(me).addClass('Highscore mod2');
				}
				$(me).children('.del').addClass('clickable').click(deleteScoreCheck);
			});
			if(!settings.isAdmin){
				$('.del').remove();
				$('.Titel_del').remove();
			};
		};
		
		var that = {
			init: function(_s){
				settings = _s;
				initTable($('#TableHighscore'));
			}
		};
		return that;
	}();
});