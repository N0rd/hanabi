$(document).ready(function(){
	$('#inputusername').on('keyup', function(){
		if ($('#inputusername').val().length > 1){
			$('#jsname').addClass('goodjs');
			$('#inputemail').removeAttr('disabled');
		}
		else{
			$('#jsname').removeClass('beforjs');
			$('#jsname').removeClass('goodjs');
		}		
	});
	$('#inputemail').on('keyup', function(){
		if (/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$/.test($('#inputemail').val())){
			$('#jsemail').addClass('goodjs');
			$('#inputpassword1').removeAttr('disabled');
		}
		else{
			$('#jsemail').removeClass('beforjs');
			$('#jsemail').removeClass('goodjs');
		}		
	});
	$('#inputpassword1').on('keyup', function(){
		if ($('#inputpassword1').val().length > 4){
			$('#jspass1').addClass('goodjs');
			$('#inputpassword2').removeAttr('disabled');
		}
		else{
			$('#jspass1').removeClass('beforjs');
			$('#jspass1').removeClass('goodjs');
		}		
	});	
	$('#inputpassword2').on('keyup', function(){
		if ($('#inputpassword1').val() == $('#inputpassword2').val()){
			$('#jspass2').addClass('goodjs');
		}
		else{
			$('#jspass2').removeClass('beforjs');
			$('#jspass2').removeClass('goodjs');
		}		
	});	
	$('#inputreg').on('click', function(event){
		if ($('.goodjs').length == 4) {
			return true;
		} else {
			return false;
		}		
	});
});