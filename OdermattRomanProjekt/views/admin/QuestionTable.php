<?php $_=$this->model?>
<div class="admin_content">
	<table id="TableQuestion" class="AdminTable Questions">
		<thead>
			<th id="headerTitelFrage">Frage</th>
			<th style="width: 40px;">Total</th>l
			<th style="width: 40px;">✔</th>
			<th style="width: 40px;">✘</th>
			<th style="width: 40px;">J</th>
			<th class="hidden">answer0</th>
			<th class="hidden">answer1</th>
			<th class="hidden">answer2</th>
			<th class="hidden">answer3</th>
		</thead>
		<tbody>
			<?php foreach ($_->data as $value){
				echo '<tr data-id='.$value->id.'>';
				echo '<td class="ques">'.$value->question.'</td>';
				echo '<td class="counts">'.$value->counts.'</td>';
				echo '<td class="counts_r"></td>';
				echo '<td class="counts_f"></td>';
				echo '<td class="counts_j">'.$value->counts_joker.'</td>';
				echo '<td style="display:none;" data-count="'.$value->counts_ans0.'" class="answer0">'.$value->answer0.'</td>';
				echo '<td style="display:none;" data-count="'.$value->counts_ans1.'" class="answer1">'.$value->answer1.'</td>';
				echo '<td style="display:none;" data-count="'.$value->counts_ans2.'" class="answer2">'.$value->answer2.'</td>';
				echo '<td style="display:none;" data-count="'.$value->counts_ans3.'" class="answer3">'.$value->answer3.'</td>';
				echo '</tr>';
			}?>
			<tr data-id="0">
				<td colspan="5">Neue Frage
				
				<td>
			
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
	require(['jquery', 'script/page/admin/questionTable'], function($, scr){
		var settings = {
			addQuestion: '<?php Uri::action('AdminQuestion', 'saveQuestion');?>',
			delQuestion: '<?php Uri::action('AdminQuestion', 'deleteQuestion');?>'
		};
		$(document).ready(function(){
			scr.initTable(settings);
		});
	});
	</script>
</div>
