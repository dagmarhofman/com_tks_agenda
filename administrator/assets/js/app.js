(function($) {

 $(document).ready(function() {

 
        $('.radio.btn-group label').addClass('btn');
        $(".btn-group label:not(.active)").click(function()
            {
                var label = $(this);
                var input = $('#' + label.attr('for'));

                if (!input.prop('checked')) {
                    label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
                    if (input.val() == '') {
                        label.addClass('active btn-primary');
                    } else if (input.val() == 0) {
                        label.addClass('active btn-danger');
                    } else {
                        label.addClass('active btn-success');
                    }
                    input.prop('checked', true);
                }
        });
        $(".btn-group input[checked=checked]").each(function()
            {
                if ($(this).val() == '') {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
                } else if ($(this).val() == 0) {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
                } else {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
                }
        });
        
        /*
 	var d = new Date();
	var year = d.getFullYear();
	var month = ("0" + (d.getMonth() + 1)).slice(-2);
	var day = ("0" + d.getDate()).slice(-2)
 
 	var options = {
		events_source: 'index.php?option=com_tks_agenda&view=items&format=json',
		view: 'month',
		tmpl_path: './components/com_tks_agenda/assets/tmpls/',
		tmpl_cache: false,
		day: 'now',
		language: 'nl-NL',
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				$(document.createElement('li'))
					.html('<a href="' + val.url + '">' + val.title + '</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h3').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
		},
		classes: {
			months: {
				general: 'label',
				inmonth: 'cal-day-inmonth',
				outmonth: 'cal-day-outmonth',
				saturday: 'cal-day-weekend',
				sunday: 'cal-day-weekend',
				holidays: 'cal-day-holiday',
				today: 'cal-day-today'
			}
		},
		modal: "#events-modal",
		views: {
			year: {
 				enable: 0
			},
			month: {
 				enable: 1
			},
			week: {
				enable: 0
			},
			day: {
				enable: 1
			}
		},modal : "#events-modal", modal_type : "ajax", modal_title : function (e) { return e.title }

	};
 
	var calendar = $('#calendar').calendar(options);

 	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function(e) {
			e.preventDefault();
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function(e) {
		e.preventDefault();
		calendar.view($this.data('calendar-view'));

		});
	});

  
	$('#show_wbn').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({display_week_numbers: val});
		calendar.view();
	});
	$('#show_wb').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({weekbox: val});
		calendar.view();
	});
	$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
		e.preventDefault();
 
	});

		$(".hasPopover").on({
		  mouseenter: function(){
		 	$(this).popover('show');
		  },
		  mouseleave: function(){
		 	$(this).popover('hide');
		  }
		}, "i");   
 });
	*/
}(jQuery)); 