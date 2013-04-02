// JavaScript Document
function begining() {
	emptyQL();
}

function emptyQL() {
	document.getElementById('qName').value="Felhasználónév";
	document.getElementById('qPass').type="text";
	document.getElementById('qPass').value="Jelszó";
}

function qNameClick() {
	document.getElementById('qName').value="";
	document.getElementById('qName').focus();
	document.getElementById('qName').select();
}

function qPassClick() {
	document.getElementById('qPass').value="";
	document.getElementById('qPass').type="password"
	document.getElementById('qPass').focus();
	document.getElementById('qPass').select();
}
function newWindow() {
	window.open('/phpmyadmin/','_blank');
}