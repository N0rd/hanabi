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
      console.log('Current player:'+output.debug.players[output.debug.currentplayer].name);
      if(output.refresh) {
        for(element in output.refresh) {
          refreshElement(element, output.refresh[element]);
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
      memoclick(this);
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

function memoclick(input) {
	gombNev=input.src;
	gombEleje=gombNev.slice(0, -5);
	gombMost=gombNev.substr(-5, 1);
	if (gombMost == '-') {gombUj = '1'};
	if (gombMost == '1') {gombUj = '0'};
	if (gombMost == '0') {gombUj = '-'};
	input.src=gombEleje + gombUj + '.gif';
	input.value=gombUj;
	return false;
}

function cancel() {
  ownCardSelectMode = false;
  clearCardSelection();
	setOwnTitle('Player 1');	
	potty = document.getElementsByName('selectcard');
	for (i = 0; i < potty.length; i++) {
		potty[i].className = 'displaynone';
		potty[i].checked = false;
	}
	document.getElementById('ownhand').className = 'showhints';
	document.getElementById('actionhints').className = 'displaynone';
	document.getElementById('actionhintwhat').className = 'displaynone';	
  showActions(['fire', 'hint', 'discard']);
	document.getElementById('hintButton').value = 'Súgás';	
	return false;
}

function fire() {
	var selected = getSelectedCard();
	if (selected != -1) {
    submitAction('build', selected);
		alert('submit: ' + selected + '. lap fellövése!');
		cancel();
		return false;
	} else {
		if (ownCardSelectMode) {
      alert('Nem választottál lapot!');
    } else {
      ownCardSelectMode = true;
      setOwnTitle('Válassz egy lapot!');
      showActions(['fire', 'cancel']);
      document.getElementById('own').className = '';
      document.getElementById('actionhints').className = 'displaynone';    
    }
		return false;
	}
}

function discard() {
	var selected = getSelectedCard();
	if (selected != -1) {
    submitAction('discard', valasztott);
		alert('submit: ' + valasztott + '. lap eldobása!');
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

function hint(playersnum) {
	document.getElementById('ownhand').className = 'displaynone';
	document.getElementById('actionhints').className = 'showhints';
	document.getElementById('actionhintwhat').className = 'displaynone';		
	setOwnTitle('Kinek súgsz?');
	for (i = playersnum + 1; i <= 5; i++ ) {
		document.getElementById('player' + i).className = 'displaynone';
	}
  showActions(['cancel']);
	return false;
}

function towhom(whomid, whomname) {
	document.getElementById('actionhints').className = 'displaynone';
	document.getElementById('whom').value = whomid;
	document.getElementById('actionhintwhat').className = 'showhints';
	document.getElementById('ownname').innerHTML = 'Mit súgsz? ' + whomname + ':';
	document.getElementById('hintButton').value = 'Másnak';
  showActions(['hint', 'cancel']);
  return false;
}

function beforesubmit(kuldo) {
  //submitAction('hint', player, hint);
	alert('submit: Az összes ' + kuldo.value + ' megsúgása...');
	cancel();
	return false;
}

function newWindow() {
	window.open('/phpmyadmin/','_blank');
}

function begining() {

}