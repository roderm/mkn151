define(
		'script/page/game/main',
		[ 'jquery', 'one' ],
		function($, one) {
			return function() {

				var settings;
				var startGame = function() {
					settings.info.points = 0;
					settings.info.message = 'Viel Glück, ';
					set_infos();
					$.ajax({
						type : "POST",
						url : settings.newGame,
						dataType : 'json'
					}).done(function(_data) {
						if (_data.success) {
							show_categories(_data);
						}
					});
				};

				var show_categories = function(_data) {
					$('.game-li').remove();
					$('#game-content')
							.append(
									$('<div class="game-li game-titel"><h2>Kategorie wählen</h2></div>'));
					$.each(_data.Kategorien, function(i, me) {
						$('#game-content').append(
								$('<div data-id="' + me.id + '" data-info="'
										+ me.descr
										+ '" class="game-li game-button">'
										+ me.val + '</div>'));
					});
					$('.game-button').click(select_categorie);
					$('#game-content')
							.append(
									$('<div id="BtnGiveUp" class="game-li game-button game-half"><h2>Aufgeben</h2></div>'));
					$('#BtnGiveUp').off().click(give_up);
					// $('.game-button').on('mouseenter',showInfo);
					// $('.game-button').on('mouseleave',hideInfo);
					// TODO: show data-info of game-button
				};

				var show_question = function(_data) {
					$('.game-li').remove();
					$('#game-content').append(
							$('<div class="game-li game-titel"><h2>'
									+ _data.question + '</h2></div>'));
					$.each(_data.answer, function(i, me) {
						$('#game-content').append(
								$('<div data-id="' + me.id
										+ '" class="game-li game-button"><h2>'
										+ me.ans + '</h2></div>'));
					});
					$('#game-content')
							.append(
									$('<div id="BtnJoker" class="game-li game-button game-half"><h2>JOKER('
											+ _data.jokers + ')</h2></div>'));
					if (_data.jokers == 0) {
						$('#BtnJoker').addClass('disabled');
					}
					$('#game-content')
							.append(
									$('<div id="BtnGiveUp" class="game-li game-button game-half"><h2>Aufgeben</h2></div>'));
					$('.game-button').click(select_answer);
					$('#BtnJoker').off().click(use_joker);
					$('#BtnGiveUp').off().click(give_up);
				}

				var select_categorie = function() {
					$('.game-button').off().click(null);
					$.ajax({
						type : "POST",
						url : settings.getQuestion,
						data : {
							katID : $(this).data('id')
						},
						dataType : 'json'
					}).done(show_question);
				};

				var select_answer = function() {
					$that = $(this);
					$('.game-button').off().click(null);
					$.ajax({
						type : "POST",
						url : settings.checkAnswer,
						data : {
							answer : $(this).data('id')
						},
						dataType : 'json'
					}).done(
							function(_data) {
								console.log(_data);
								if (_data.success) {
									settings.info.points += 30;
									settings.info.message = 'Super,';
									$that.css('border-color', 'greenyellow');
									setTimeout(function() {
										show_categories(_data);
									}, 2000);
								} else {
									settings.info.message = 'Schade,';
									$that.css('border-color', 'red');
									$('[data-id="' + _data.answer + '"]').css(
											'border-color', 'greenyellow');
									setTimeout(function() {
										show_main([ 'GAME OVER' ]);
									}, 2000);
								}
								set_infos();
							});

				};

				var show_main = function(_messages) {
					$('.game-li').remove();
					$.each(_messages, function(i, me) {
						$('#game-content').append(
								$('<div class="game-li game-titel"><h2>' + me
										+ '</h2></div>'));
					});
					$('#game-content')
							.append(
									$('<div id="Btn_NewGame" class="game-li game-button"><h2>Neues Spiel</h2></div>'));
					$('#Btn_NewGame').click(startGame);
				};

				var give_up = function() {
					// quitGame
					$.ajax({
						type : "POST",
						url : settings.quitGame,
						dataType : 'json'
					}).done(
							function(_data) {
								show_main([ 'Gratuliere, sie haben '
										+ _data.score + ' punkte erzielt.' ]);
								one.contentReplace(settings.reloadHighscore,
										null, $('#TopTenScores').children()
												.first());
								$('*[data-id="' + _data.gameID + '"]').css(
										'background-color', '#FFCC00');
							});
				};

				var use_joker = function() {
					$.ajax({
						type : "POST",
						url : settings.useJoker,
						dataType : 'json'
					}).done(
							function(_data) {
								$('[data-id="' + _data.n0 + '"]').off()
										.addClass('disabled');
								$('[data-id="' + _data.n1 + '"]').off()
										.addClass('disabled');
								$('#BtnJoker').html(
										'JOKER(' + _data.joker + ')');
								if (_data.joker == 0) {
									$('#BtnJoker').off();
									$('#BtnJoker').addClass('disabled');
								}
							});
				};

				var set_infos = function() {
					$('#UserGreets').html(
							settings.info.message + ' ' + settings.info.user);
					$('#UserPoints').html('Points: ' + settings.info.points);
				};

				var that = {
					init : function(_s) {
						settings = _s;
						$('#Btn_NewGame').click(startGame);
						$('#BtnLogout').click(function() {
							$.ajax({
								type : "POST",
								url : settings.logout,
								dataType : 'json'
							}).done(function(_data) {
								window.location.href = _data.uri;
							});
						});
						set_infos();
						if (settings.hasFrage) {
							$.ajax({
								type : "POST",
								url : settings.backToGame,
								dataType : 'json'
							}).done(function(_data) {
								show_question(_data);
								set_infos();
							});
						}
					}
				};
				return that;
			}();
		});