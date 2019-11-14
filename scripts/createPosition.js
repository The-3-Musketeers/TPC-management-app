// select all branch function
let select_all = This => {
  let $id = This.id;
  let $checked = This.checked;
  if ($checked) $("." + $id).prop("checked", true);
  else $("." + $id).prop("checked", false);
};

$(document).ready(() => {
  $("input:checkbox").on("change", function() {
    let degreeObj = {};
    degreeObj.degree_checked = [];
    degreeObj.degree_unchecked = [];
    $(".course input:checkbox").each(function() {
      let $this = $(this);
      if ($this.is(":checked")) {
        degreeObj.degree_checked.push($this.attr("id"));
      } else {
        degreeObj.degree_unchecked.push($this.attr("id"));
      }
    });
    $.post("../api/getDegreeBranch.php", function(degreeBranch) {
      for (let key of Object.keys(degreeBranch)) {
        let degree_name = "#" + key + "_branch";
        $(degree_name).empty();
        if (degreeObj.degree_checked.includes(key)) {
          let $department = degreeBranch[key];
          $(degree_name).append(
            "<div><span class='badge badge-secondary'>" + key + "</span></div>"
          );
          $(degree_name).append(
            "<div class='form-check'><input id='" +
              key +
              "' class='form-check-input' name='branch[]' type='checkbox' onChange='select_all(this)'><label class='form-check-label'>All</label></div>"
          );
          $department.forEach($D => {
            $r = $(
              "<div class='form-check form-check-inline'><input class='form-check-input " +
                key +
                "' name='branch[]' type='checkbox' value=" +
                $D +
                "><label class='form-check-label'>" +
                $D +
                "</label></div>"
            );
            $(degree_name).append($r);
          });
        } else {
          $(degree_name).empty();
        }
      }
    });
  });
});
