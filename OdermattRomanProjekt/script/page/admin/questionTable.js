define('script/page/admin/questionTable', [ 'jquery', 'one' ],
		function($, one) {
			return function() {

				var $CategorieRow;
				var headerSettings;
				var tableSettings;
				var initHeaderTable = function($table) {
					$table.addClass('Questions');
					$.each($table.children('tbody').children('tr'), function(i,
							me) {
						if (i % 2 == 0) {
							$(me).addClass('header mod1 clickable');
						} else {
							$(me).addClass('header mod2 clickable');
						}
					});
				};
				
				var initQuesTable = function($table){
					$table.addClass('Questions');
					var newWidth = $table.width() -85;
					$('#headerTitelFrage').width(newWidth);
					$.each($table.children('tbody').children('tr'), function(i, me){
						if($(me).data('id')!==0){
							$(me).children('td.ques').width(newWidth);
							$(me).children('td.counts_r').html($(me).children('td.answer0').data('count'));
							var wrong = $(me).children('td.answer1').data('count');
							wrong += $(me).children('td.answer2').data('count');
							wrong += $(me).children('td.answer3').data('count');
							$(me).children('td.counts_f').html(wrong);
						}
						
						if (i % 2 == 0) {
							$(me).addClass('Question mod1 clickable');
						} else {
							$(me).addClass('Question mod2 clickable');
						}
					});
				};
				
				var openQuestion = function(){
					$('#TableQuestion tbody tr').off();
					$('#TableQuestion').children('.clickable').removeClass('clickable');
					if ($(this).data('id') !== 0) {
						$currentRow = $(this);						
					} else {
						$currentRow = $('<tr data-id="0"/>');
						$currentRow.insertBefore($(this));
					}
					$cell = $('<td colspan="5"></td>');
					var controlls = [];
					controlls.push({type : 'text',id : 'questionID',addClass : 'hidden',value : $currentRow.data('id')});
					controlls.push({type : 'text',label : 'Frage (' + $currentRow.children('.counts').html() + ')',addClass : 'adminText',id : 'frageText',value : $currentRow.children('.ques').html()});
					controlls.push({type : 'text',label : 'richtige Anwort (' + $currentRow.children('.answer0').data('count') + ')',addClass : 'adminText',id : 'answer0Txt',value : $currentRow.children('.answer0').html()});
					controlls.push({type : 'text',label : 'falsche Anwort 1 (' + $currentRow.children('.answer1').data('count') + ')',addClass : 'adminText',id : 'answer1Txt',value : $currentRow.children('.answer1').html()});
					controlls.push({type : 'text',label : 'falsche Anwort 2 (' + $currentRow.children('.answer2').data('count') + ')',addClass : 'adminText',id : 'answer2Txt',value : $currentRow.children('.answer2').html()});
					controlls.push({type : 'text',label : 'falsche Anwort 3 (' + $currentRow.children('.answer3').data('count') + ')',addClass : 'adminText',id : 'answer3Txt',value : $currentRow.children('.answer3').html()});
					controlls.push({type : 'button',label : 'Abbrechen',id : 'myButton2',addClass : 'red',html : 'style="margin-left: 160px;"',click : closeEditQuestion});
					controlls.push({type : 'button',label : 'Speichern',id : 'myButton1',addClass : 'green',click : saveQuestion});
					if ($(this).data('id') !== 0) {
						controlls.push({type : 'button',label : 'Löschen',id : 'myButton3',addClass : 'red',click : deleteQuestionCheck});
					}
					$cell.makeForm({
						theme : 'form',
						formID : 'editQuestionForm',
						action : '',
						methode : 'POST',
						controlls : controlls
					});
					$cell = $('<tr/>').append($cell).off();
					$cell.insertAfter($currentRow.first('tr'));
				};
				
				var closeEditQuestion = function(){
					if ($currentRow.data('id') === 0) {
						$currentRow.remove();
						$('#editQuestionForm').parents('tr:first').remove();
					} else {
						$('#editQuestionForm').parents('tr:first').replaceWith(
								$currentRow);
					}
					$('#TableQuestion tbody tr').click(openQuestion);
					$('#TableQuestion').children('tbody').children('tr')
							.removeClass('mod1').removeClass('mod2');
					initQuesTable($('#TableQuestion'));
					return;
				};
				var saveQuestion = function(){
					if($('#frageText').val() == "" || $('#answer0Txt').val() == "" || $('#answer1Txt').val() == "" || $('#answer2Txt').val() == "" || $('#answer3Txt').val() == ""){
						alert("Keines der Felder darf leer sein.");
						return false;
					}
					if($('#frageText').val().length > 50 || $('#answer0Txt').length > 50 || $('#answer1Txt').val().length > 50 || $('#answer2Txt').length > 50 || $('#answer3Txt').val().length > 50){
						alert("Die Frage darf nicht mehr als 50 Zeichen haben und die Anworten nicht mehr als 20.");
						return false;
					}
					$.ajax({
						type : "POST",
						url : tableSettings.addQuestion,
						data : {
							questionID: $('#questionID').val(),
							katID : headerSettings.katID,
							question : $('#frageText').val(),
							answer0 : $('#answer0Txt').val(),
							answer1 : $('#answer1Txt').val(),
							answer2 : $('#answer2Txt').val(),
							answer3 : $('#answer3Txt').val()
						},
						dataType : 'json'
					}).done(function(data) {
						if (data.success) {
							one.contentReplace(headerSettings.submitUrl, {katID : headerSettings.katID}, $('.admin_content'));
						}
					});
				};
				var deleteQuestionCheck = function(){
          var ctrls = [];
					ctrls.push({type : 'message',text: 'Beim Löschen werden auch die Statistiken gelöscht und Highscore einträge angepasst!'});
					ctrls.push({type : 'button',label : 'Abbrechen',id : 'myButton2',addClass : 'red right', click: closeDialog});
					ctrls.push({type : 'button',label : 'Fortfahren',id : 'myButton1',addClass : 'green right',click : deleteQuestion});
					$('#dialogShadow').removeClass('hidden');
					$('#dialogShadow').makeDialog({
					  controlls: ctrls
					})
				};
				var closeDialog = function(){
				  $('#dialogShadow').children().remove();
				  $('#dialogShadow').addClass('hidden');
				}
				var deleteQuestion = function(){
					$.ajax({
						type : "POST",
						url : tableSettings.delQuestion,
						data : {
							id : $('#questionID').val()
						}
					}).done(function(data) {
						$currentRow.data('id', 0);
						closeDialog();
						closeEditQuestion();
					});
				};
				
				var that = {
					initHeader : function(_s) {
						headerSettings = _s;
						initHeaderTable($('#QuestionDropdown'));
						$('#QuestionDropdown tbody tr').click(function() {
							$('#QuestionDropdown').addClass('hidden');
							headerSettings.showMenu = false;
							headerSettings.katID = $(this).data('id');
							$('#Question_DropdownBtn').html($(this).children('.bez').html());
							one.contentReplace(headerSettings.submitUrl, {katID : headerSettings.katID}, $('.admin_content'));
						});
						$('#Question_DropdownBtn').click(function(){
							if(headerSettings.showMenu){
								$('#QuestionDropdown').addClass('hidden');
								headerSettings.showMenu = false;
							}else{
								$('#QuestionDropdown').removeClass('hidden');
								headerSettings.showMenu = true;
							}
						});
					},
					initTable : function(_s) {
						tableSettings = _s;
						initQuesTable($('#TableQuestion'));
						$('#TableQuestion tbody tr').click(openQuestion);
					}
				};
				return that;
			}();
		});