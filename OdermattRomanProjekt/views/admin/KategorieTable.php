<?php $_=$this->model ?>
<div id="ContentKategorie" class="content-block">
	<table id="TableKategorie" class="AdminTable">
		<thead>
			<tr>
				<th style="width: 30%">Bezeichnung</th>
				<th style="width: 70%">Beschreibung</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($_->data as $value){
				echo '<tr data-id='.$value->id.'>';
				echo '<td class="bez">'.$value->bezeichnung.'</td>';
				echo '<td class="descr">'.$value->beschreibung.'</td>';
				echo '</tr>';
			}?>
			<tr data-id="0">
			<td colspan="2">Neue Kategorie<td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
	require(['jquery', 'script/page/admin/kategorieTable'], function($, scr){
		var settings = {
			addKategorie: '<?php Uri::action('AdminKategorie', 'saveKategorien');?>',
			delKategorie: '<?php Uri::action('AdminKategorie', 'deleteKategorien');?>'
		};
		$(document).ready(function(){
			scr.init(settings);
		});
	});
	</script>
</div>