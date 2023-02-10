// add a click event to the button with id "calculate-savings-button"
document.getElementById("calculate-savings-button").addEventListener("click", function() {
  
  // get the values of inputs with ids "monthly-contribution" and "number-of-months"
  var monthlyContribution = parseFloat(document.getElementById("monthly-contribution").value);
  var numberOfMonths = parseFloat(document.getElementById("number-of-months").value);
  
  // calculate the total savings by multiplying the monthly contribution by the number of months
  var totalSavings = monthlyContribution * numberOfMonths;
  
  // display the total savings in an element with id "total-savings"
  document.getElementById("total-savings").innerHTML = "Total savings: $" + totalSavings.toFixed(2);
});

// function ends here
