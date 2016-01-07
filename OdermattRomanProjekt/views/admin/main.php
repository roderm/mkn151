<?php $_=$this->model ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    <link rel="stylesheet" type="text/css" href="style/admin.css">
    <link rel="stylesheet" type="text/css" href="style/dialog.css">
    <link rel="stylesheet" type="text/css" href="style/controlls.css">
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
    <script>
      require(['jquery', 'script/page/admin/main'], function($, admin){
          var stats = {
              getKategorien: '<?php Uri::action('AdminKategorie');?>',
              getQuestions: '<?php Uri::action('AdminQuestion');?>',
              getScores: '<?php Uri::action('Highscore', 'getMain');?>',
              getTools: '<?php Uri::action('Admin', 'getTools');?>'
          };
          $(document).ready(function(){
              admin.init(stats); 
          });
      })
    </script>
    </head>
    <body>
    <div id="dialogShadow" class="shadow"></div>
    <div class="ContentBlock">
		<div id="TitleKategorien" class="ContentTitle Kategorien">
		<img src="<?php echo Uri::file('media/TitelKatgorien.png'); ?>" alt="Kategorien" width="110">
        </div>
	</div>
	<div class="ContentBlock">
        <div id="TitleQuestions" class="ContentTitle Questions">
        <img src="<?php echo Uri::file('media/TitelFragen.png'); ?>" alt="Fragen" width="110">
        </div>
   </div>
	<div class="ContentBlock">
        <div id="TitleScore" class="ContentTitle Highscore">
        <img src="<?php echo Uri::file('media/TitelHighscore.png'); ?>" alt="Highscore" width="110">
        </div>
   </div>
	<div class="ContentBlock">
        <div id="TitleTools" class="ContentTitle Tools">
        <img src="<?php echo Uri::file('media/TitelLogout.png'); ?>" alt="Logout" width="110">
        </div>
    </div>
    </body>
</html>
