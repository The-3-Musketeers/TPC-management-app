$(document).ready(() => {
  $(".alert")
    .delay(5000)
    .slideUp(500, function() {
      $(this).alert("close");
    });
});
