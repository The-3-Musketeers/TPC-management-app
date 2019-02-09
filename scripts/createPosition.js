$(document).ready(() => {
  let btech = $("#btech");
  let mtech = $("#mtech");

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
      if (!mtech[0].checked) {
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
      if (!btech[0].checked) {
        warn();
      }
    }
  };

  add_btech();
  btech.on("click",() => {
    add_btech();
  });
  mtech.on("click",() => {
    add_mtech();
  });

});
