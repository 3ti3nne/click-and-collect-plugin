(function ($) {
  //Disabling dates
  //beforeShowDay pass through days, get the num associated with .getDay() (i.e Sunday = 0, Monday = 1 etc)
  //we tune in a function to compare with our days from db, return an array [false] to disable day.

  $("#datepicker").datepicker({
    minDate: +1,
    beforeShowDay: function (date) {
      if (date.getDay() == php_vars[0] || date.getDay() == php_vars[1]) {
        return [false, "closed"];
      } else {
        return [true];
      }
    },
  });
})(jQuery);
