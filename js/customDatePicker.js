jQuery(document).ready(function () {
  jQuery.ajax({
    url: "wp-content/plugins/click-and-collect-plugin/controllers/utilities.php",
    method: "GET",
    dataType: "json",
  });
});

/* function getDays([days]) {
  days.forEach((day) => {
    let d = new Date(),
      year = d.getYear(),
      daysArray = [];

    d.setDate(1);

    // Get the first Monday in the month
    while (d.getDay() !== 1) {
      d.setDate(d.getDate() + 1);
    }
    console.log(d);

    // Get all the other Mondays in the month
    while (d.getYear() === year) {
      let pushDate = new Date(d.getTime());
      daysArray.push(
        pushDate.getDate() +
          "-" +
          (pushDate.getMonth() + 1) +
          "-" +
          pushDate.getFullYear()
      );
      d.setDate(d.getDate() + 7);
    }
  });
  console.log(daysArray);
}
jQuery(function ($) {
  $("#datepicker").datepicker({
    beforeShowDay: function (date) {
      var day = date.getDay();
      if (date.day() === "Monday") {
        console.log(day);
      }
    },
  });
});
 */
