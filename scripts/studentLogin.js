$(document).ready(function() {
  var loginType="0";
  $('#login-type').on('click',function() {
    loginType=$(this).val();
    login_type();
  });
  function login_type() {
    if(loginType == "1"){
      console.log('admin');
      $("#roll-number-div").hide();
      $("#remember-div").hide();
      $("#signup-div").hide();
    } else {
      $("#roll-number-div").show();
      $("#remember-div").show();
      $("#signup-div").show();
    }
  }
});
