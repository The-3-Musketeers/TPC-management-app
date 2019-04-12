$(document).ready(() => {
	let btech = $("#update_btech");
	let mtech = $("#update_mtech");
	let msc = $("#update_msc");
	let phd = $("#update_phd");

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
			if (!mtech[0].checked && !msc[0].checked && !phd[0].checked) {
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
			if (!btech[0].checked && !msc[0].checked && !phd[0].checked) {
				warn();
			}
		}
	};

	let add_msc = () => {
		if (msc[0].checked) {
			if ($("#update_btech_branch").text() == "Please Select a Course") {
				$("#update_btech_branch").empty();
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
				$("#update_msc_branch").append($r);
				$i++;
			});
		} else {
			$("#update_msc_branch").empty();
			if (!mtech[0].checked && !btech[0].checked && !phd[0].checked) {
				warn();
			}
		}
	};

	let add_phd = () => {
		if (phd[0].checked) {
			if ($("#update_btech_branch").text() == "Please Select a Course") {
				$("#update_btech_branch").empty();
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
				$("#update_phd_branch").append($r);
				$i++;
			});
		} else {
			$("#update_phd_branch").empty();
			if (!mtech[0].checked && !btech[0].checked && !msc[0].checked) {
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
	if (course.indexOf("msc") !== -1) {
		msc[0].checked = true;
		add_msc();
	}
	if (course.indexOf("phd") !== -1) {
		phd[0].checked = true;
		add_phd();
	}

	if (course.indexOf("btech") == -1 && course.indexOf("mtech") == -1 && course.indexOf("msc") == -1 && course.indexOf("phd") == -1) {
		warn();
	}

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
