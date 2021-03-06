// JavaScript Document
function submitAction(action, param1, param2) {
  $.ajax({
    url: 'action.php',
    data: {action: action, param1: param1, param2: param2},
    type: 'post',
    dataType: 'json',
    success: function(output) {
      /*debug*/
      console.log(output);
      if(output.output.error) {
        alert(output.output.error);
      } else {
        if(output.refresh) {
          for(element in output.refresh) {
            refreshElement(element, output.refresh[element]);
          }
        }
        if(output.logs) {
          addToLog(output.logs);
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
	$('.hintselector').on('mouseover', function(event){
		var property = $(this).attr('id').substr(5,1);
		var playerHand = '#player'+hintTargetPlayer+'hand';
		if (isNaN(property)){
			var selectedImg ='img[class="cardhand '+property+'"]';
			$(playerHand).children(selectedImg).addClass('marked');
		} else {
			var selectedImg ='img[alt="'+property+'"]';
			$(playerHand).children(selectedImg).addClass('marked');
		}
		return false;
	});
		$('.hintselector').on('mouseout', function(event){
			var playerHand = '#player'+hintTargetPlayer+'hand';			
			$(playerHand).children('img').removeClass('marked');
	});
  $('#cancelButton').click(cancel);
  $('#hintButton').click(hint);
  $('#fireButton').click(fire);
  $('#discardButton').click(discard);
  $('#actionhints').on('click', '.toplayer', function() {
    selectHintPlayer($(this).attr('data-playerplace'), $(this).val());
  });
  $('#actionhintwhat').on('click', '.hintselector', function(){
    var hintdata = $(this).attr('id');  
		selectHintHint(hintdata.substr(5,1));
  });
  currentPlayerName = $('#ownname').html();
  refreshGame();
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

function addToLog(logs) {
  var newLogDiv = $('<div class="logmessage"></div>');
  for(log in logs) {
    newLogDiv.append(logs[log] + '<br />');
  }
	$('#logandchat').prepend(newLogDiv);

}

function memoClick(input) {
	var buttonName = input.src;
	var size = buttonName.length;
	var buttonBegin = buttonName.slice(0, size-7);
	var buttonOld = buttonName.substr(size-7, 3);
  var card = $(input).parent().index();
  var info = $(input).attr('name');
  $.ajax({
    url: 'ownhand.php',
    data: {card: card, info: info},
    type: 'post',
    dataType: 'json',
    success: function(output) {
      if(output.status) {
        input.src = buttonBegin + output.info + '.gif';
        input.value = output.info;
      }
    }
  });
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
	setOwnTitle('Mit súgsz? <br />' + name + ':');
	var targetPlayerHand = '#player'+targetPlayer+'hand';
	$('.hinthelp').html(function() {
			var labelFor = $(this).attr('for').substr(5,1);
			var finded = new Array(), text;
			if (isNaN(labelFor)){
					$(targetPlayerHand + ' img').each(function() {
            if ($(this).attr('class').substr(9,1) ==  labelFor) {
							finded.push($(this).index());
						}
					});
			} else {
					$(targetPlayerHand + ' img').each(function() {
            if ($(this).attr('alt') ==  labelFor) {
	 						finded.push($(this).index());
						}
					});
			}
			if (finded.length == 0) {
				text = 'nincs';
			} else {
				text = finded.join('., ')+'.';
			}
			return text;
		});
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

function refreshGame() {
  submitAction();
}