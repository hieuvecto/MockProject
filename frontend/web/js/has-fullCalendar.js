function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function initCalendar() {
	var calendar = $('#calendar');
	
	$('#calendar').fullCalendar({
		locale: 'vi',
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		buttonText: {
			today:    'Hôm nay',
			month:    'Tháng',
			week:     'Tuần',
			day:      'Ngày',
		},
		allDaySlot: false,
		defaultView: 'agendaWeek',
		// events: "/index.php?r=sub-pitch%2Fget-events&id=" + getParameterByName('pitch_id'),
		timeFormat: 'H:mm',
		slotLabelFormat: 'HH(:mm)',
		eventRender: function(event, element, view) {
			if (view.name == 'agendaWeek') {
				var div = element.find(".fc-time");
				var div2 = div.clone();
				var str = div.text().split(" - ");
				div.html(`<span>${str[0]} -</span>`);
				div2.html(`<span>${str[1]}</span>`);
				div.after(div2);
			}	    
		},
	});
}

$('document').ready(function() {
	initCalendar();
});

$('.btn-modal').on('click', function() {
	var sub_pitch_id = $(this).data('id');
	var calendar = $('#calendar');
	var modal = $('#myModal');
	
	modal.data('sub-pitch', sub_pitch_id);
	modal.modal('show');
});

$('.link-modal').on('click', function(event) {
	event.preventDefault()
	var sub_pitch_id = $(this).data('id');
	var calendar = $('#calendar');
	var modal = $('#myModal');
	
	modal.data('sub-pitch', sub_pitch_id);
	modal.modal('show');
});

$('#myModal').on('shown.bs.modal', function (e) {
	var calendar = $('#calendar');
	var sub_pitch_id = $(this).data('sub-pitch');
	calendar.fullCalendar('render');
	calendar.fullCalendar('removeEventSources');
	calendar.fullCalendar('addEventSource', 
		"/sub-pitch/get-events?id=" + sub_pitch_id);
});