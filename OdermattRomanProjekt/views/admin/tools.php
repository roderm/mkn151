<?php $_=$this->model?>
<div class="content-block Tools">
		<div id="BtnLogout">
			<img src="<?php echo Uri::file('media/logout.png'); ?>" alt="Logout"
				width="20"> Logout
		</div>
	<script type="text/javascript">
	require(['jquery', 'script/page/admin/questionTable'], function($, scr){
		$('#BtnLogout').click(function() {
			$.ajax({
				type : "POST",
				url : '<?php Uri::action('Login', 'logout'); ?>',
				dataType : 'json'
			}).done(function(_data){
				window.location.href = _data.uri;
			});
		});
	});
	</script>
</div>
