define('script/page/admin/kategorieTable',
		[ 'jquery', 'myDialog' ], function($) {
			return function() {

				var $currentRow;
				var lsettings = {};

				var openKategorien = function() {
					$('#TableKategorie tbody tr').off();
					$('.clickable').removeClass('clickable');
					if ($(this).data('id') !== 0) {
						$currentRow = $(this);
					} else {
						$currentRow = $('<tr data-id="0"/>');
						$currentRow.append($('<td class="bez">[new]</td>'));
						$currentRow.append($('<td class="descr"/>'));
						$currentRow.insertBefore($(this));
					}
					$cell = $('<td colspan="2"></td>');
					var controlls = [];
					controlls.push({type : 'text',label : 'Bezeichnung',addClass : 'adminText',id : 'bezTxt',value : $currentRow.children('.bez').html()});
					controlls.push({type : 'textarea',label : 'Beschreibung',addClass : 'adminText',id : 'descTxt',value : $currentRow.children('.descr').html(),html : 'rows="4"'});
					controlls.push({type : 'text',id : 'katID',addClass : 'hidden',value : $(this).data('id')});
					controlls.push({type : 'button',label : 'Abbrechen',id : 'myButton2',addClass : 'red',html : 'style="margin-left: 160px;"',click : closeEditKategorie});
					controlls.push({type : 'button',label : 'Speichern',id : 'myButton1',addClass : 'green',click : saveKategorie}); 
					                 
					if ($(this).data('id') !== 0) {
						controlls.push({type : 'button',label : 'Löschen',id : 'myButton3',addClass : 'red',click : deleteKategorieCheck});
					}
					// controlls.push();
					$cell.makeForm({
						theme : 'form',
						formID : 'editKatForm',
						action : '',
						methode : 'POST',
						controlls : controlls
					});
					$cell = $('<tr/>').append($cell).off();
					$cell.insertAfter($currentRow.first('tr'));
				};
        var deleteKategorieCheck = function(){
          var ctrls = [];
					ctrls.push({type : 'message',text: 'Beim Löschen werden auch die zugeordneten Fragen gelöscht, deren Statistik und Highscoreeinträge angepasst!'});
					ctrls.push({type : 'button',label : 'Abbrechen',id : 'myButton2',addClass : 'red right', click: closeDialog});
					ctrls.push({type : 'button',label : 'Fortfahren',id : 'myButton1',addClass : 'green right',click : deleteKategorie});
					$('#dialogShadow').removeClass('hidden');
					$('#dialogShadow').makeDialog({
					  controlls: ctrls
					})
				};
				var closeDialog = function(){
				  $('#dialogShadow').children().remove();
				  $('#dialogShadow').addClass('hidden');
				}
				var deleteKategorie = function() {
					$.ajax({
						type : "POST",
						url : lsettings.delKategorie,
						data : {
							id : $('#katID').val()
						}
					}).done(function(data) {
						$currentRow.data('id', 0);
						closeDialog();
						closeEditKategorie();
					});
				}
				var saveKategorie = function() {
					$.ajax({
						type : "POST",
						url : lsettings.addKategorie,
						data : {
							katID : $('#katID').val(),
							bezTxt : $('#bezTxt').val(),
							descTxt : $('#descTxt').val()
						},
						dataType : 'json'
					}).done(function(data) {
						if (data.success) {
							$currentRow.data('id', data.row.id);
							$currentRow.children('.bez').html(data.row.bez);
							$currentRow.children('.descr').html(data.row.desc);
							closeEditKategorie();
						}
					});
				};

				var closeEditKategorie = function() {
					if ($currentRow.data('id') === 0) {
						$currentRow.remove();
						$('#editKatForm').parents('tr:first').remove();
					} else {
						$('#editKatForm').parents('tr:first').replaceWith(
								$currentRow);
					}
					$('#TableKategorie tbody tr').click(openKategorien);
					$('#TableKategorie').children('tbody').children('tr')
							.removeClass('mod1').removeClass('mod2');
					initTable($('#TableKategorie'));
					return;
				};
				var initTable = function($table) {
					$table.addClass('Kategorien');
					$.each($table.children('tbody').children('tr'), function(i,
							me) {
						if (i % 2 == 0) {
							$(me).addClass('Kategorien mod1 clickable');
						} else {
							$(me).addClass('Kategorien mod2 clickable');
						}
					});
				};

				var that = {
					init : function(_s) {
						lsettings = _s;
						$('#TableKategorie tbody tr').click(openKategorien);
						initTable($('#TableKategorie'));
					}
				};
				return that;
			}();
		});