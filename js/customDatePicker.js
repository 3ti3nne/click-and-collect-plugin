(function ($) {
  console.log(php_vars);

  $("#datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    beforeShowDay: $.datepicker.noWeekends,
  });
})(jQuery);

/* jQuery(function($) {
  let disabledDates = [];
  $("form").submit(function(e) {
    dateValue = document.getElementById('disableDate').value
    disabledDates.push(dateValue)
    $("#customerCalendar").datetimepicker("refresh")
    return false
  });
	$("#disableDays input").change(function(){
   $("#customerCalendar").datetimepicker("refresh")
  })
  $("#disableDate").datepicker({
    dateFormat: 'yy-mm-dd',
    minDate: +1
  });

  $("#customerCalendar").datetimepicker({
    beforeShowDay: function(date) {
      let dateString = jQuery.datepicker.formatDate('yy-mm-dd', date);
      let disabledDays = $("#disableDays input").map(function(){return this.checked}).get(); // array of booleans
      if ( disabledDays[date.getDay()])
      	return [false];
      return [disabledDates.indexOf(dateString) == -1]
    },
    timeFormat: 'HH',
    minDate: +1
  });

});
 */
