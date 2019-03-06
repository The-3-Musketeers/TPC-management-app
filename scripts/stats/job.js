$(document).ready(function(){
  const btech_labels = [
    "CSE",
    "EE",
    "ME",
    "CH",
    "CE"
  ];

  const mtech_labels = [
    "MECH",
    "MNC",
    "NANO",
    "CSE",
    "COMM",
    "ME",
    "CE",
    "MSE",
    "VLSI"
  ];

  var btech_data = {
    datasets: [{
      label: 'B.Tech',
      data: [20, 15, 8, 5, 4],
      backgroundColor: [
            "#ff4281",
            "#ffa037",
            "#ffd54d",
            "#00c3c0",
            "#2d94ed"
          ]
    }],
    labels: btech_labels
  };

  var mtech_data = {
    datasets: [{
      label: 'M.Tech',
      data: [20, 15, 8, 5, 4, 6, 7, 8, 9],
      backgroundColor: [
            "#dc3545",
            "#ff4281",
            "#ffa037",
            "#fd7e14",
            "#ffd54d",
            "#4dd4ed",
            "#17a2b8",
            "#28a745",
            "#2d94ed"
          ]
    }],
    labels: mtech_labels
  };

  var ctx1 = $("#btech");
  var ctx2 = $("#mtech");

  //options
  var btech_options = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Btech",
      fontSize: 18,
      fontColor: "#111"
    },
    legend: {
      display: true,
      position: "left",
      labels: {
        fontColor: "#333",
        fontSize: 16
      }
    }
  };

  var mtech_options = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Mtech",
      fontSize: 18,
      fontColor: "#111"
    },
    legend: {
      display: true,
      position: "left",
      labels: {
        fontColor: "#333",
        fontSize: 16
      }
    }
  };

  // Create pie charts
  var btechPieChart = new Chart(ctx1,{
      type: 'pie',
      data: btech_data,
      options: btech_options
  });

  var mtechPieChart = new Chart(ctx2,{
      type: 'pie',
      data: mtech_data,
      options: mtech_options
  });
});
