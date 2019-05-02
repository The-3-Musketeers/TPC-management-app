// select all branch function
let select_all = (This) => {
  let $id = This.id;
  let $checked = This.checked;
  if ($checked)
    $("." + $id).prop('checked', true);
  else
    $("." + $id).prop('checked', false);
};

$(document).ready(() => {
  let btech = $("#btech");
  let mtech = $("#mtech");
  let msc = $("#msc");
  let phd = $("#phd");
  let warn = () => {
    $("#btech_branch").text("Please Select a Course");
  };

  // Add Btech Branch
  let add_btech = () => {
    if (btech[0].checked) {
      if ($("#btech_branch").text() == "Please Select a Course") {
        $("#btech_branch").empty();
      }
      const $department = ["CS", "EE", "ME", "CE", "CB"];
      $("#btech_branch").append("<div><span class='badge badge-secondary'>BTech</span></div>");
      $("#btech_branch").append("<div class='form-check'><input id='btech' class='form-check-input' name='branch[]' type='checkbox' onChange='select_all(this)'><label class='form-check-label'>All</label></div>");
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input btech' name='branch[]' type='checkbox' value=" +
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

  // Add Mtech Branch
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
      $("#mtech_branch").append("<div><span class='badge badge-secondary'>MTech</span></div>");
      $("#mtech_branch").append("<div class='form-check'><input id='mtech' class='form-check-input' name='branch[]' type='checkbox' onChange='select_all(this)'><label class='form-check-label'>All</label></div>");
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input mtech' name='branch[]' type='checkbox' value=" +
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

  // Add MSC Branch
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
      $("#msc_branch").append("<div><span class='badge badge-secondary'>MSC</span></div>");
      $("#msc_branch").append("<div class='form-check'><input id='msc' class='form-check-input' name='branch[]' type='checkbox' onChange='select_all(this)'><label class='form-check-label'>All</label></div>");
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input msc' name='branch[]' type='checkbox' value=" +
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

  // Add PHD Branch
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
      $("#phd_branch").append("<div><span class='badge badge-secondary'>PHD</span></div>");
      $("#phd_branch").append("<div class='form-check'><input id='phd' class='form-check-input' name='branch[]' type='checkbox' onChange='select_all(this)'><label class='form-check-label'>All</label></div>");
      let $i = 0;
      $department.forEach(($D) => {
        $r = $(
          "<div class='form-check form-check-inline'><input class='form-check-input phd' name='branch[]' type='checkbox' value=" +
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
