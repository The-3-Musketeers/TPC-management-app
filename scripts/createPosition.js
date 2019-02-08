$(document).ready(function() {
  var btech = $("#btech");
  var mtech = $("#mtech");
  add_btech();
  btech.on("click", function() {
    add_btech();
  });
  mtech.on("click", function() {
    add_mtech();
  });

  function warn() {
    $("#btech_branch").text("Please Select a Course");
  }

  function add_btech() {
    if (btech[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
      var $department = ["CS", "EE", "ME", "CE", "CB"];
      for (var $i = 0; $i < $department.length; $i++) {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
            $department[$i] +
            "><label class='form-check-label'>" +
            $department[$i] +
            "</label></div>"
        );
        $("#btech_branch").append($r);
      }
    } else {
      $("#btech_branch").empty();
      if (!mtech[0].checked) {
        warn();
      }
    }
  }
  function add_mtech() {
    if (mtech[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
      var $department = [
        "Mechatronics",
        "Mathematics & Computing",
        "Nano Science & Technology",
        "Computer Science & Engineering",
        "Communication System Engineering",
        "Mechanical Engineering",
        "Civil & Infrastructure Engineering",
        "Materials Science & Engineering",
        "VLSI & Embedded Systems"
      ];
      var $dept_value = [
        "mech",
        "mnc",
        "nano",
        "cse",
        "comm",
        "me",
        "ce",
        "mse",
        "vlsi"
      ];
      for (var $i = 0; $i < $department.length; $i++) {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
            $dept_value[$i] +
            "><label class='form-check-label'>" +
            $department[$i] +
            "</label></div>"
        );
        $("#mtech_branch").append($r);
      }
    } else {
      $("#mtech_branch").empty();
      if (!btech[0].checked) {
        warn();
      }
    }
  }
});
