$(document).ready(() => {
  let btech = $("#btech");
  let mtech = $("#mtech");
  let msc = $("#msc");
  let phd = $("#phd");

  let warn = () => {
    $("#btech_branch").text("Please Select a Course");
  };

  let add_btech = () => {
    if (btech[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
      const $department = ["CS", "EE", "ME", "CE", "CB"];
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
          $D +
          "><label class='form-check-label'>" +
          $D +
          "</label></div>"
        );
        $("#btech_branch").append($r);
      });
    } else {
      $("#btech_branch").empty();
      if (!mtech[0].checked && !msc[0].checked && !phd[0].checked) {
        warn();
      }
    }
  };

  let add_mtech = () => {
    if (mtech[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
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
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
          $dept_value[$i] +
          "><label class='form-check-label'>" +
          $D +
          "</label></div>"
        );
        $("#mtech_branch").append($r);
        $i++;
      });
    } else {
      $("#mtech_branch").empty();
      if (!btech[0].checked && !msc[0].checked && !phd[0].checked) {
        warn();
      }
    }
  };

  let add_msc = () => {
    if (msc[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
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
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
          $dept_value[$i] +
          "><label class='form-check-label'>" +
          $D +
          "</label></div>"
        );
        $("#msc_branch").append($r);
        $i++;
      });
    } else {
      $("#msc_branch").empty();
      if (!btech[0].checked && !mtech[0].checked && !phd[0].checked) {
        warn();
      }
    }
  };

  let add_phd = () => {
    if (phd[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
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
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
          $dept_value[$i] +
          "><label class='form-check-label'>" +
          $D +
          "</label></div>"
        );
        $("#phd_branch").append($r);
        $i++;
      });
    } else {
      $("#phd_branch").empty();
      if (!btech[0].checked && !mtech[0].checked && !msc[0].checked) {
        warn();
      }
    }
  };

  add_btech();
  btech.on("click", () => {
    add_btech();
  });
  mtech.on("click", () => {
    add_mtech();
  });
  msc.on("click", () => {
    add_msc();
  });
  phd.on("click", () => {
    add_phd();
  });

});
