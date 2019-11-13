$(document).ready(() => {
  $(".alert")
    .delay(20000)
    .slideUp(500, function() {
      $(this).alert("close");
    });
});
