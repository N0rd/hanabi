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
      document.getElementById('ownname').innerHTML = (output.debug.players[output.debug.currentplayer].name);
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
  $('#own').on('click', '#ownhand input', function(event) {
    event.preventDefault();
    memoclick(this);
  });
});


function memoclick(input) {
	buttonName=input.src;
	buttonBegin=buttonName.slice(0, -5);
	buttonOld=buttonName.substr(-5, 1);
	if (buttonOld == '-') {buttonNew = '1'};
	if (buttonOld == '1') {buttonNew = '0'};
	if (buttonOld == '0') {buttonNew = '-'};
	input.src=buttonBegin + buttonNew + '.gif';
	input.value=buttonNew;
	return false;
}

function elenorzes(kapott) {
	valami = document.getElementsByName(kapott);
	sorszam = 0;
	for (i = 0; i < valami.length; i++) {
		if (valami[i].checked) {
			sorszam = i + 1;
		}
	}	
	return sorszam;
}

function cancel() {
	document.getElementById('ownname').innerHTML = 'Player 1';	
	potty = document.getElementsByName('selectcard');
	for (i = 0; i < potty.length; i++) {
		potty[i].className = 'displaynone';
		potty[i].checked = false;
	}
	document.getElementById('ownhand').className = 'showhints';
	document.getElementById('actionhints').className = 'displaynone';
	document.getElementById('actionhintwhat').className = 'displaynone';	
	document.getElementById('canceling').className = 'displaynone';
	document.getElementById('droping').className = 'action';
	document.getElementById('fireing').className = 'action';
	document.getElementById('hinting').className = 'action';
	document.getElementById('hinting').value = 'Súgás';	
	return false;
}

function fire(handsize) {
	valasztott = elenorzes('selectcard'); 
	if (valasztott != 0) {
	    submitAction('build', valasztott - 1);
		cancel();
		return false;
	} else {
		if (document.getElementById('canceling').className == 'action') {alert('Nem választottál lapot!');	}
		for (i = 1; i <= handsize; i++) {
			document.getElementById('selectcard' + i).className = 'select';
		}	
		document.getElementById('canceling').className = 'action';
		document.getElementById('hinting').className = 'hidden';
		document.getElementById('droping').className = 'displaynone';
		document.getElementById('own').className = '';
		document.getElementById('actionhints').className = 'displaynone';
		return false;
	}
}

function drop(handsize) {
	valasztott = elenorzes('selectcard'); 
	if (valasztott != 0) {
	    submitAction('discard', valasztott - 1);
		cancel();
		return false;
	} else {
		if (document.getElementById('canceling').className == 'action') {alert('Nem választottál lapot!');	}
		for (i = 1; i <= handsize; i++) {
			document.getElementById('selectcard' + i).className = 'select';
		}	
		document.getElementById('canceling').className = 'action';
		document.getElementById('hinting').className = 'displaynone';
		document.getElementById('fireing').className = 'hidden';
		return false;
	}
}

function hint(playersnum) {
	document.getElementById('ownhand').className = 'displaynone';
	document.getElementById('actionhints').className = 'showhints';
	document.getElementById('actionhintwhat').className = 'displaynone';		
	document.getElementById('ownname').innerHTML = 'Kinek súgsz?';
	for (i = playersnum + 1; i <= 5; i++ ) {
		document.getElementById('player' + i).className = 'displaynone';
	}
	document.getElementById('canceling').className = 'action';
	document.getElementById('hinting').className = 'displaynone';
	document.getElementById('fireing').className = 'displaynone';
	document.getElementById('droping').className = 'displaynone';
	return false;
}

function towhom(whomid, whomname) {
	document.getElementById('actionhints').className = 'displaynone';
	document.getElementById('whom').value = whomid;
	document.getElementById('actionhintwhat').className = 'showhints';
	document.getElementById('ownname').innerHTML = 'Mit súgsz? ' + whomname + ':';
	document.getElementById('hinting').className = 'action';
	document.getElementById('hinting').value = 'Másnak';
	document.getElementById('canceling').className = 'action';
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