<?php $_=$this->model ?>
<div class="content-block">
	<div class="Questions header">
		<div class="label_container" style="width: 300px;"><h2>Kategorie auswählen:</h2></div>
		<div id="Question_DropDown" class="Questions" style="display: inline-block;">
			<div id="Question_DropdownBtn"></div>
			<table id="QuestionDropdown" class="AdminTable">
				<tbody id="Dropdown_table">
					<?php foreach ($_->data as $value){
						echo '<tr data-id='.$value->id.'>';
						echo '<td class="bez">'.$value->bezeichnung.'</td>';
						echo '</tr>';
					}?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="admin_content hidden"></div>
</div>
<script type="text/javascript">
require(['jquery', 'script/page/admin/questionTable'], function($, src){
	var settings = {
		submitUrl: '<?php Uri::action('AdminQuestion', 'getQuestionTable')?>',
		showMenu: true
	};
	$(document).ready(function(){
		src.initHeader(settings);
	});
});
</script>
</div>