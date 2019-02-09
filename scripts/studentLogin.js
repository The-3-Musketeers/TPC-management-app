$(document).ready(() => {
  let login_type = (loginType) => {
    if(loginType == "1"){
      $("#roll-number-div").hide();
      $("#remember-div").hide();
      $("#signup-div").hide();
    } else {
      $("#roll-number-div").show();
      $("#remember-div").show();
      $("#signup-div").show();
    }
  };
  $('#login-type').on('change', () => {
    let loginType = $('#login-type').val();
    login_type(loginType);
  }); 
});
