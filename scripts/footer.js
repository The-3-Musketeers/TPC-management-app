function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

      // Pick a remaining element...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;

      // And swap it with the current element.
      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }

    return array;
}

var arr = [["Praduman", "PrP-11"], ["Harsh", "harsh-jindal"], ["Divyanshu", "DNS-404"]];
var github = "https://github.com/";
arr = shuffle(arr);

function setValus(arr) {
    document.getElementById("link1").href=github+arr[0][1];
    document.getElementById("link2").href=github+arr[1][1];
    document.getElementById("link3").href=github+arr[2][1];
    document.getElementById("dev1").innerHTML=arr[0][0] + ",";
    document.getElementById("dev2").innerHTML=arr[1][0] + ",";
    document.getElementById("dev3").innerHTML=arr[2][0];
};
setValus(arr);
