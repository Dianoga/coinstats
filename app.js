var secondInt = 1;
var minuteInt = secondInt * 60;
var hourInt = minuteInt * 60;
var dayInt = hourInt * 24;

$().ready(function() {
	$.getJSON('fetch.php', function(data) {
		$.each(data, function(i, pool) {
			fetch_pool(pool);
		});
	});
});

function fetch_pool(pool) {
	var parentEl = $('<div />').attr('id', pool.id).addClass('pool col-md-3').appendTo('.pools');
	var element = $('<div />').addClass('panel panel-default').appendTo(parentEl);
	var headerEl = $('<div class="panel-heading">').html($('<h2 class="panel-title">').text(pool.name)).appendTo(element);
	headerEl.wrapInner($('<a />').attr('href', pool.link).attr('target', '_blank'));
	var bodyEl = $('<div />').addClass('panel-body').appendTo(element);
	var loadingEl = $('<div />').addClass('progress progress-striped active').append($('<div />').addClass('progress-bar').width('100%')).prependTo(bodyEl);
	
	$.getJSON('fetch.php', {pool: pool.id}, function(data) {
		console.log(data);
		if(data.unconfirmed) {
			bodyEl.append('<div class="pending"><strong>Unconfirmed</strong>: ' + data.unconfirmed + '</div>');
		}
		bodyEl.append('<div><strong>Confirmed</strong></div>');
		$.each(data.balance, function(i, bal) {
			var balEl = $('<div class="balance" />');
			balEl.append('<span class="type">' + bal.type + ': </span>');
			balEl.append('<span class="value">' + bal.value + '</span>');
			bodyEl.append(balEl);
		});
		
		if(data.workers.length > 0) {
			listEl = $('<ul class="list-group"/>').appendTo(element);
			$.each(data.workers, function(i, worker) {
				var workerEl = $('<li class="list-group-item worker">');
				$('<span class="speed badge">').text(parseInt(worker.speed)).appendTo(workerEl);
				workerEl.append('<div class="name">' + worker.name + '</div>');
				if(worker.last_share) {
					var timeAgo = time_ago(worker.last_share);
					workerEl.append('<div class="last_share">' + timeAgo.output + '</div>');
				}
				listEl.append(workerEl);
			});
		}
		
		loadingEl.hide();
	});
}

function time_ago(timestamp) {	
	var timeAgo = {};
	timeAgo.diff = (Date.now()/1000) - timestamp;
	timeAgo.days = Math.floor(timeAgo.diff / dayInt);
	timeAgo.hours = Math.floor((timeAgo.diff - (timeAgo.days * dayInt)) / hourInt);
	timeAgo.minutes = Math.floor((timeAgo.diff - (timeAgo.days * dayInt) - (timeAgo.hours * hourInt)) / minuteInt);
	
	if(timeAgo.days < 7) {
		if(timeAgo.minutes == 0 && timeAgo.hours == 0 && timeAgo.days == 0) {
			timeAgo.output = 'Just now';
		} else {
			timeAgo.output = ' ago';
			if(timeAgo.minutes > 0) {
				timeAgo.output = timeAgo.minutes + 'minutes ' + timeAgo.output;
			}
			if(timeAgo.hours > 0) {
				timeAgo.output = timeAgo.hours + ' hours ' + timeAgo.output;
			}
			if(timeAgo.days > 0) {
				timeAgo.output = timeAgo.days + ' days ' + timeAgo.output;
			}
		}
	} else {
		timeAgo.output = new Date(timestamp * 1000).toLocaleDateString();
	}
	
	return timeAgo;
}
