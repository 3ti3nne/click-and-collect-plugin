let jququ = jQuery.noConflict();

jququ(function () {
  jququ("#datepicker").datepicker({
    onSelect: function (date) {
      alert(date);
    },
    selectWeek: true,
    inline: true,
    startDate: "01/01/2000",
    firstDay: 1,
  });
});
