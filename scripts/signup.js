$(document).ready(function(){
  $(".reload").on("click", () => {
    $("#captcha-image").attr("src","../util/captcha.php");
  });
});
