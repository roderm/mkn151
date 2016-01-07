/*


*/

(function($){
	
	var defaultOps = {
			data: [
			       {col1: "id or whatever", col2: "value or whatever"}
			       ],
			col: [
			      {name: 'col1', titel: 'spalte 1', width:'20px', visible: true},
			      {name: 'col2', titel: 'spalte 2', width: '20px', visible:true}
			      ]
	};
	
	$.fn.initDT = function(_ops){
		
		_ops = defaultOps;
		this.append('<table></table>');
		
		var head = $('<thead><tr></tr></thead>')
		$.each(_ops.col, function(i){
			var ncol = $('<td id=' + _ops.col[i].name + '>' + _ops.col[i].titel + '</td>');
			if(_ops.col[i].visible === false){
				ncol.css('hidden', 'true');
			}
			head.children('tr').append(ncol);
		});
		$(this).children('table').append(head);
		
		var body = $('<tbody></tbody>');
		$.each(_ops.data, function(i){
			var nrow = $('<tr></tr>');
			$.each(_ops.col, function(j){
				nrow.append('<td>'+ _ops.data[i][_ops.col[j].name] +'</td>')
			});
			body.append(nrow);
		});
		$(this).children('table').append(body);
		console.log("wehere is the table?");
	}
	
	
}(jQuery));