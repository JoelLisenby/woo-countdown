/***

A simple javascript countdown timer by Joel Lisenby
https://www.joellisenby.com/
Version 1.0 / 2016-11-16

***/
var jblcountdown = function( id, begin, end ) {

	var jt = this;
	jt.obj = document.getElementById( id );
	jt.now = new Date( Date.now() );
	jt.begin = begin;
	jt.end = end;
	
	jt.start = function() {
		jt.render();
		jt.interval = window.setInterval( jt.render, 1000 );
	};
	
	jt.render = function() {
		jt.now = new Date();
	
		jt.timeleft = jt.end.getTime() - jt.now.getTime();
		
		var x = jt.timeleft / 1000;
		jt.seconds = Math.floor( x % 60 );
		x /= 60;
		jt.minutes = Math.floor( x % 60 );
		x /= 60;
		jt.hours = Math.floor( x % 24 );
		x /= 24;
		jt.days = Math.floor( x );
		
		var html = '';
		
		if( jt.timeleft > 0 && jt.now.getTime() > jt.begin.getTime() ) {
			html += jt.days > 0 ? '<div class="days"><span class="time">'+ jt.days +'</span><span class="label">Day'+ ( jt.days > 1 || jt.days == 0 ? 's' : '' ) +'</span></div>' : '';
			html += jt.hours > 0 ? '<div class="hours"><span class="time">'+ jt.hours +'</span><span class="label">Hour'+ ( jt.hours > 1 || jt.hours == 0 ? 's' : '' ) +'</span></div>' : '';
			html += jt.minutes > 0 ? '<div class="minutes"><span class="time">'+ jt.minutes +'</span><span class="label">Minute'+ ( jt.minutes > 1 || jt.minutes == 0 ? 's' : '' ) +'</span></div>' : '';
			html += jt.seconds >= 0 ? '<div class="seconds"><span class="time">'+ jt.seconds +'</span><span class="label">Second'+ ( jt.seconds > 1 || jt.seconds == 0 ? 's' : '' ) +'</span></div>' : '';
		} else if( jt.now.getTime() < jt.begin.getTime() && jt.now.getTime() <= jt.end.getTime() ) {
			html += '<div>Available Soon!</div>';
		} else {
			html += '<div>Time\'s up!</div>';
		}
		
		jt.obj.innerHTML = html;
	};
	
	jt.start();

};
