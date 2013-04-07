// JavaScript Document
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


function newWindow() {
	window.open('/phpmyadmin/','_blank');
	
}

function begining() {

}