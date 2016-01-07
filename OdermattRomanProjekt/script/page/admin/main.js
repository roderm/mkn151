/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('script/page/admin/main', ['jquery', 'one', 'myDialog'], function($, one){
   return function(){
	   //var before;
       var that = {
           init: function(s){
             $('#dialogShadow').addClass('hidden');
        	   $('.ContentTitle').click(function(){
        		   // TODO : remove before Content
        		   $('.content-block').remove();
        		   $('.loading').remove();
        		   $(this).after($('<div class="loading">').append($('<div class="loadingIcon">')));
        		   $('.loading').css('width', '1000px').css('height', '60%');
        		   
        		   switch($(this).attr('id')){
        		   case 'TitleKategorien':
        			   one.contentReplace(s.getKategorien, null, $('.loading'));
        			   break;
        		   case 'TitleQuestions':
        			   one.contentReplace(s.getQuestions, null, $('.loading'));
        			   break;
        		   case 'TitleScore':
        			   one.contentReplace(s.getScores, null, $('.loading'));
        			   break;
        		   case 'TitleTools':
        			   one.contentReplace(s.getTools, null, $('.loading'));
        		   }
        	   });
           }
       };
       return that;
   }();
});
