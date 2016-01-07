<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php $_=$this->model ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    <link rel="stylesheet" type="text/css" href="<?php Uri::file('style/main.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php Uri::file('style/dialog.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php Uri::file('style/controlls.css')?>">
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
    <body style="padding: 0;">
    <div class="main">
    </div>
    </body>
    <script>
	    require(['jquery', 'script/page/login'], function($, main){
		    var settings = {
				submitUrl: '<?php Uri::action('Login', 'loggin');?>'
		    };
	       $(document).ready(function(){
	           main.init(settings);
	       });
	    });
    </script>
</html>
