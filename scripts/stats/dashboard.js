$(document).ready(function(){
  const company_categories = [
    "A1",
    "B1",
    "B2"
  ];

  var company_data = {
    datasets: [{
      label: 'Companies',
      data: [20, 15, 8],
      backgroundColor: [
            "#ff4281",
            "#00c3c0",
            "#2d94ed"
          ]
    }],
    labels: company_categories
  };

  var ctx1 = $("#company-category");

  //options
  var company_options = {
    responsive: true,
    title: {
      display: true,
      position: "top",
      text: "Category Wise distribution",
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
  var companyPieChart = new Chart(ctx1,{
      type: 'pie',
      data: company_data,
      options: company_options
  });


  // Students stats
  var data=[
   {
      "course":"btech",
      "branch":"CSE",
      "placed":"51",
      "remaining":"4"
   },
   {
      "course":"btech",
      "branch":"EE",
      "placed":"48",
      "remaining":"7"
   },
   {
      "course":"btech",
      "branch":"ME",
      "placed":"40",
      "remaining":"15"
   },
   {
      "course":"btech",
      "branch":"CH",
      "placed":"18",
      "remaining":"11"
   },
   {
      "course":"btech",
      "branch":"CE",
      "placed":"15",
      "remaining":"15"
   }
  ];
  var btech_labels = [];
  var btech_placed = [];
  var btech_remaining = [];

  for(var i in data) {
    if(data[i].course=="btech"){
      btech_labels.push(data[i].branch);
      btech_placed.push(data[i].placed);
      btech_remaining.push(data[i].remaining);
    }
  }

  var chartdata = {
    labels: btech_labels,
    datasets : [
      {
        label: 'Placed',
        backgroundColor: '#00c3c0',
        data: btech_placed
      },
      {
        label: 'Remaining',
        backgroundColor: '#2d94ed',
        data: btech_remaining
      }
    ]
  };

  var ctx2 = $("#students");

  var barGraph = new Chart(ctx2, {
    type: 'bar',
    data: chartdata
  });
});
