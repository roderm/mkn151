define('one', [ 'jquery' ], function($) {
	return function() {

		// var username;
		// var katName;

		var that = {
			contentReplace : function(_url, _parameter, $remove) {
				$.ajax({
					type : 'POST',
					url : _url,
					data : _parameter,
					dataType : "HTML"
				}).done(function(_data) {
					$remove.replaceWith(_data);
				});
			}
		};
		return that;
	}();
});