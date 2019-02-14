$(document).ready(() => {
    let btech = $("#update_btech");
    let mtech = $("#update_mtech");

    let warn = () => {
        $("#update_btech_branch").text("Please Select a Course");
    };

    let course = $("#course").val();
    course = course.split(',');
    let branch = $("#branch").val();
    branch = branch.split(',');

    let add_btech = () => {
        if (btech[0].checked) {
            if ($("#update_btech_branch").text() == "Please Select a Course") {
                $("#update_btech_branch").empty();
            }
            const $department = ["CS", "EE", "ME", "CE", "CB"];
            $department.forEach(($D) => {
                if (branch.indexOf($D) !== -1) {
                    $r = $(
                        "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
                        $D +
                        " checked><label class='form-check-label'>" +
                        $D +
                        "</label></div>"
                    );
                }
                else {
                    $r = $(
                        "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
                        $D +
                        "><label class='form-check-label'>" +
                        $D +
                        "</label></div>"
                    );
                }
                $("#update_btech_branch").append($r);
            });
        } else {
            $("#update_btech_branch").empty();
            if (!mtech[0].checked) {
                warn();
            }
        }
    };

    let add_mtech = () => {
        if (mtech[0].checked) {
            if ($("#update_btech_branch").text() == "Please Select a Course") {
                $("#update_btech_branch").empty();
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
                if (branch.indexOf($dept_value[$i]) !== -1) {
                    $r = $(
                        "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
                        $dept_value[$i] +
                        " checked><label class='form-check-label'>" +
                        $D +
                        "</label></div>"
                    );
                }
                else {
                    $r = $(
                        "<div class='form-check form-check-inline'><input class='form-check-input' name='branch[]' type='checkbox' value=" +
                        $dept_value[$i] +
                        "><label class='form-check-label'>" +
                        $D +
                        "</label></div>"
                    );
                }
                $("#update_mtech_branch").append($r);
                $i++;
            });
        } else {
            $("#update_mtech_branch").empty();
            if (!btech[0].checked) {
                warn();
            }
        }
    };
    if (course.indexOf("btech") !== -1) {
        btech[0].checked = true;
        add_btech();
    }
    if (course.indexOf("mtech") !== -1) {
        mtech[0].checked = true;
        add_mtech();
    }
    btech.on("click", () => {
        add_btech();
    });
    mtech.on("click", () => {
        add_mtech();
    });

});
