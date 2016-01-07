<?php $_=$this->model ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    <link rel="stylesheet" type="text/css" href="<?php Uri::file('style/main.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php Uri::file('style/controlls.css'); ?>">
    <script src="script/requirejs.js"></script>
    <script>
	    require.config({
	    	shim: {
	        	'myDialog':{
	        		deps: ['jquery']
	        	}
	    	},
	        paths: {
	            jquery: 'script/jquery2.2',
	            one: 'script/page/one',
	            myDialog: 'script/plugin/dialog'
	        }
	    });
    </script>
    </head>
    <body>
    	<div id="game-content">
			<div id="Btn_NewGame" class="game-li game-button"><h2>Neues Spiel</h2></div>
    	</div>
    	<div id="score-content">
    	  <div id="BtnLogout">
    	     <img src="<?php echo Uri::file('media/logout.png'); ?>" alt="Logout" width="20">
    	    Logout
    	  </div>
	    	<div id="infoPanel">
				<h2 id="UserGreets"></h2>
				<h3 id="UserPoints"></h3>
				<!--<h3 class="UserTime">Zeit:</h3>-->
			</div>
			<div id="TopTenScores">
			  <?php new routFactory('Highscore', 'getMain'); ?>
			</div>
    	</div>
    </body>
    <script type="text/javascript">
	require(['jquery', 'script/page/game/main'], function($, page){
		var settings = {
			scoreUrl: '<?php Uri::action('AdminScore', 'getScore');?>',
			newGame: '<?php Uri::action('Gamer', 'newGame');?>',
			getQuestion: '<?php Uri::action('Gamer', 'getQuestion');?>',
			checkAnswer: '<?php Uri::action('Gamer', 'checkAnswer');?>',
			giveUp: '<?php Uri::action('Gamer', 'checkAnswer');?>',
			quitGame: '<?php Uri::action('Gamer','quitGame');?>',
			useJoker: '<?php Uri::action('Gamer', 'useJoker');?>',
			backToGame: '<?php Uri::action('Gamer', 'backToGame');?>',
			logout: '<?php Uri::action('Login', 'logout'); ?>',
			reloadHighscore: '<?php Uri::action('Highscore', 'getMain'); ?>',
			hasFrage: <?php echo ($_->hasFrage?'true':'false');?>,
			info: {
			  user: '<?php echo $_->user;?>',
			  points: <?php echo $_->points;?>,
			  message: '<?php echo $_->message;?>',
			}
		};
		$(document).ready(function(){
			page.init(settings);
		});
	});
    </script>
</html>