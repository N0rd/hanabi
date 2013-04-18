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
  $('#ownhand').on('click', 'input', function(event) {
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
	var buttonBegin = buttonName.slice(0, -5);
	var buttonOld = buttonName.substr(-5, 1);
	var buttonNew;
	if (buttonOld == '-') {buttonNew = '1'};
	if (buttonOld == '1') {buttonNew = '0'};
	if (buttonOld == '0') {buttonNew = '-'};
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