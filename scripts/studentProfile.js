$(document).ready(() => {
  let add_departemnt = () => {
    let course = $("#course").val();
    $.post("../api/getDegreeBranch.php", function(degreeBranch) {
      for (let key of Object.keys(degreeBranch)) {
        if (course == key) {
          let dept = $("#department").val();
          $("#department").empty();
          const $department = degreeBranch[key];
          let $r;
          if ($department.indexOf(dept) > -1) {
            $r = $("<option value=" + dept + ">" + dept + "</option>");
            $("#department").append($r);
          } else {
            $r = $(
              "<option value=" + null + ">Select your department</option>"
            );
            $("#department").append($r);
          }
          $department.forEach($D => {
            if (dept != $D)
              $r = $("<option value=" + $D + ">" + $D + "</option>");
            $("#department").append($r);
          });
        }
      }
    });
  };

  add_departemnt();
  $("#course").on("change", () => {
    add_departemnt();
  });
});
