<?php $_=$this->model ?>
<div id="ContentHighscore" class="content-block">
	<table id="TableHighscore" class="AdminTable">
		<thead>
			<tr>
				<th>Rang</th>
				<th>Gewichtete Punkte</th>
				<th>Gamer-Name</th>
				<th>Startzeit</th>
				<th>Punkte</th>
				<th>Dauer</th>
				<th>Kategorien</th>
				<th class="Titel_del">löschen</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach ($_->data as $value){
				echo '<tr data-id='.$value->GameID.'>';
				echo '<td>'.$i.'</td>';
				echo '<td>'.$value->GamerMainScore.' ('.$value->GamerScore.')</td>';
				echo '<td>'.$value->GamerName.'</td>';
				echo '<td>'.$value->GameStart.'</td>';
				echo '<td>'.$value->GamerPoints.'</td>';
				echo '<td>'.$value->GameDuration.'</td>';
				echo '<td style="text-overflow: ellipsis;">'.$value->GameCategories.'</td>';
				echo '<td class="del">✘</td>';
				echo '</tr>';
				$i++;
			}?>
		</tbody>
	</table>
	<script type="text/javascript">
	require(['jquery', 'script/page/admin/highscore'], function($, scr){
		var settings = {
			isAdmin: <?php echo $_->isAdmin; ?>,
			gameId: <?php echo $_->gameID; ?>,
			delScore: '<?php Uri::action('Highscore', 'deleteCol');?>'
		};
		$(document).ready(function(){
			scr.init(settings);
		});
	});
	</script>
</div>