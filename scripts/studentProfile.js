$(document).ready(function() {
  add_departemnt();
  $("#course").on("change", function() {
    add_departemnt();
  });
  function add_departemnt() {
    var course = $("#course").val();
    if (course == "Btech") {
      var dept = $("#department").val();
      $("#department").empty();
      var $r;
      var $department = ["CS", "EE", "ME", "CE", "CB"];
      if ($department.indexOf(dept) > -1) {
        $r = $("<option value=" + dept + ">" + dept + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      var $i = 0;
      for ($i = 0; $i < $department.length; $i++) {
        if (dept != $department[$i])
          $r = $(
            "<option value=" +
              $department[$i] +
              ">" +
              $department[$i] +
              "</option>"
          );
        $("#department").append($r);
      }
    } else if (course == "Mtech") {
      var dept = $("#department").val();
      $("#department").empty();
      var $r;
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
      if ($dept_value.indexOf(dept) > -1) {
        var $ind = $dept_value.indexOf(dept);
        $r = $("<option value=" + dept + ">" + $department[$ind] + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      var $i = 0;
      for ($i = 0; $i < $department.length; $i++) {
        if (dept != $dept_value[$i])
          $r = $(
            "<option value=" +
              $dept_value[$i] +
              ">" +
              $department[$i] +
              "</option>"
          );
        $("#department").append($r);
      }
    } else {
      var dept = $("#department").val();
      $("#department").empty();
      var $r;
      var $department = ["Mathematics", "Physics", "Chemistry"];
      var $dept_value = ["math", "phy", "chem"];
      if ($dept_value.indexOf(dept) > -1) {
        var $ind = $dept_value.indexOf(dept);
        $r = $("<option value=" + dept + ">" + $department[$ind] + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      var $i = 0;
      for ($i = 0; $i < $department.length; $i++) {
        if (dept != $dept_value[$i])
          $r = $(
            "<option value=" +
              $dept_value[$i] +
              ">" +
              $department[$i] +
              "</option>"
          );
        $("#department").append($r);
      }
    }
  }
});
