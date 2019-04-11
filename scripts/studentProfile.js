$(document).ready(() => {
  let add_departemnt = () => {
    let course = $("#course").val();
    if (course === "Btech") {
      let dept = $("#department").val();
      $("#department").empty();
      let $r;
      const $department = ["CS", "EE", "ME", "CE", "CB"];
      if ($department.indexOf(dept) > -1) {
        $r = $("<option value=" + dept + ">" + dept + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      $department.forEach(($D) => {
        if (dept != $D) $r = $("<option value=" + $D + ">" + $D + "</option>");
        $("#department").append($r);
      });
    } else if (course === "Mtech") {
      let dept = $("#department").val();
      $("#department").empty();
      let $r;
      const $department = [
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
      const $dept_value = [
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
        let $ind = $dept_value.indexOf(dept);
        $r = $("<option value=" + dept + ">" + $department[$ind] + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      let $i = 0;
      $department.forEach(($D) => {
        if (dept != $dept_value[$i])
          $r = $("<option value=" + $dept_value[$i] + ">" + $D + "</option>");
        $("#department").append($r);
        $i++;
      });
    } else if (course === "Msc") {
      let dept = $("#department").val();
      $("#department").empty();
      let $r;
      const $department = [
        "Mathematics",
        "Physics",
        "Chemistry"
      ];
      const $dept_value = [
        "math",
        "phy",
        "chem"
      ];
      if ($dept_value.indexOf(dept) > -1) {
        let $ind = $dept_value.indexOf(dept);
        $r = $("<option value=" + dept + ">" + $department[$ind] + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      let $i = 0;
      $department.forEach(($D) => {
        if (dept != $dept_value[$i])
          $r = $("<option value=" + $dept_value[$i] + ">" + $D + "</option>");
        $("#department").append($r);
        $i++;
      });
    } else if (course === "PHD") {
      let dept = $("#department").val();
      $("#department").empty();
      let $r;
      const $department = [
        "Computer Science & Engineering",
        "Electrical Engineering",
        "Mechanical Engineering",
        "Civil & Environment Engineering",
        "Chemical & Biochemical Engineering",
        "Material Science & Engineering",
        "Mathematics",
        "Physics",
        "Chemistry",
        "Humanities and Social Sciences"
      ];
      const $dept_value = [
        "cse_phd",
        "ee_phd",
        "me_phd",
        "ce_phd",
        "cb_phd",
        "mse_phd",
        "math_phd",
        "phy_phd",
        "chem_phd",
        "humanities_phd"
      ];
      if ($dept_value.indexOf(dept) > -1) {
        let $ind = $dept_value.indexOf(dept);
        $r = $("<option value=" + dept + ">" + $department[$ind] + "</option>");
        $("#department").append($r);
      } else {
        $r = $("<option value=" + null + ">Select your department</option>");
        $("#department").append($r);
      }
      let $i = 0;
      $department.forEach(($D) => {
        if (dept != $dept_value[$i])
          $r = $("<option value=" + $dept_value[$i] + ">" + $D + "</option>");
        $("#department").append($r);
        $i++;
      });
    }
  };

  add_departemnt();
  $("#course").on("change", () => {
    add_departemnt();
  });
});