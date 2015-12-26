$(document).ready(function(){
    $("input[type=range]").change(function(){
		var input = $(this);
		var val = input.val(),
			max = input.attr('max');
		var pct = Math.round(val * 1.0 / max * max * 100) / max;
		input.parent('.input-group').find('.pct').html(pct);
	});
    
});
