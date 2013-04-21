// JavaScript Document
function submitAction(action, param1, param2) {
  $.ajax({
    url: 'action.php',
    data: {action: action, param1: param1, param2: param2},
    type: 'post',
    dataType: 'json',
    success: function(output) {
      console.log(output);
      /*debug*/
      currentPlayerName = output.debug.players[output.debug.currentplayer].name;
      setOwnTitle(output.debug.players[output.debug.currentplayer].name);
      if(output.refresh) {
        for(element in output.refresh) {
          refreshElement(element, output.refresh[element]);
        }
      }
      if(output.logs) {
        for(log in output.logs) {
          addToLog(output.logs[log]);
        }
      }
    }
  });
}

function refreshElement(element, content) {
  $('#' + element).html(content);
}

$(document).ready(function(){
  ownCardSelectMode = false;
  $('#ownhand').on('click', 'input.memo', function(event) {
    if(!ownCardSelectMode) {
      event.preventDefault();
      memoClick(this);
    }
  });
  $('#ownhand').on('click', '.owncard', function(event) {
    if(ownCardSelectMode) {
      event.preventDefault();
      clearCardSelection();
      $(this).addClass('selected');
    }
  });
	$('#inputusername').on('keyup', function(){
		if ($('#inputusername').val().length > 1){
			$('#jsname').addClass('goodjs');
			$('#inputemail').removeAttr('disabled');
		}
		else{
			$('#jsname').removeClass('goodjs');
		}		
	});
	$('#inputemail').on('keyup', function(){
		if (/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]*)$/.test($('#inputemail').val())){
			$('#jsemail').addClass('goodjs');
			$('#inputpassword1').removeAttr('disabled');
		}
		else{
			$('#jsemail').removeClass('goodjs');
		}		
	});
	$('#inputpassword1').on('keyup', function(){
		if ($('#inputpassword1').val().length > 4){
			$('#jspass1').addClass('goodjs');
			$('#inputpassword2').removeAttr('disabled');
		}
		else{
			$('#jspass1').removeClass('goodjs');
		}		
	});	
	$('#inputpassword2').on('keyup', function(){
		if ($('#inputpassword1').val() == $('#inputpassword2').val()){
			$('#jspass2').addClass('goodjs');
		}
		else{
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
  $('#cancelButton').click(cancel);
  $('#hintButton').click(hint);
  $('#fireButton').click(fire);
  $('#discardButton').click(discard);
  $('#actionhints').on('click', '.toplayer', function() {
    selectHintPlayer($(this).attr('data-playerplace'), $(this).val());
  });
  $('#actionhintwhat').on('click', '.hintselector', function(){
    selectHintHint($(this).attr('data-id'));
  });
  currentPlayerName = $('#ownname').html();
});

function getSelectedCard() {
  return $('#ownhand .selected').index();
}

function clearCardSelection() {
  $('#ownhand .owncard').removeClass('selected');
}

function setOwnTitle(title) {
  $('#ownname').html(title);
}

function showActions(actions) {
  $('.action').hide();
  for(a in actions) {
    $('#' + actions[a] + 'Button').show();
  }
}

function addToLog(log) {
  var newLogDiv = $('<div class="logmessage"></div>').html(log);
	$('#logandchat').prepend(newLogDiv);
}

function memoClick(input) {
	var buttonName = input.src;
	var size = buttonName.length;
	var buttonBegin = buttonName.slice(0, size-7);
	var buttonOld = buttonName.substr(size-7, 3);
	var buttonNew;
	if (buttonOld == 'unk') {buttonNew = 'thy'};
	if (buttonOld == 'thy') {buttonNew = 'thn'};
	if (buttonOld == 'thn') {buttonNew = 'unk'};
	input.src = buttonBegin + buttonNew + '.gif';
	input.value = buttonNew;
	return false;
}

function cancel() {
	$('#ownhand .owncard').removeClass('selectable');	
  ownCardSelectMode = false;
  setOwnTitle(currentPlayerName);
  clearCardSelection();
	$('#ownhand').show();
	$('#actionhints').hide();
	$('#actionhintwhat').hide();
  showActions(['fire', 'hint', 'discard']);
	$('#hintButton').val('Súgás');
	return false;
}

function fire() {
	$('#ownhand .owncard').addClass('selectable');
	var selected = getSelectedCard();
	if (selected != -1) {
    submitAction('build', selected);
		cancel();
		return false;
	} else {
		if (ownCardSelectMode) {
      alert('Nem választottál lapot!');
    } else {
      ownCardSelectMode = true;
      setOwnTitle('Válassz egy lapot!');
      showActions(['fire', 'cancel']);
      $('actionhints').hide;
    }
		return false;
	}
}

function discard() {
	$('#ownhand .owncard').addClass('selectable');
	var selected = getSelectedCard();
	if (selected != -1) {
    submitAction('discard', selected);
		cancel();
		return false;
	} else {
		if (ownCardSelectMode) {
      alert('Nem választottál lapot!');
    } else {
      ownCardSelectMode = true;
      setOwnTitle('Válassz egy lapot!');
      showActions(['discard', 'cancel']);
    }
		return false;
	}
}

function hint() {
	$('#ownhand').hide();
	$('#actionhints').show();
	$('#actionhintwhat').hide();
	setOwnTitle('Kinek súgsz?');
  showActions(['cancel']);
	return false;
}

function selectHintPlayer(targetPlayer, name) {
	hintTargetPlayer = targetPlayer;
	$('#ownhand').hide();
	$('#actionhints').hide();
	$('#actionhintwhat').show();
	setOwnTitle('Mit súgsz? ' + name + ':');
	$('#hintButton').val('Nem neki');
  showActions(['hint', 'cancel']);
  return false;
}

function selectHintHint(hint) {
  submitAction('hint', hintTargetPlayer, hint);
	cancel();
	return false;
}

function newWindow() {
	window.open('/phpmyadmin/','_blank');
}