//get menu id
let x= document.getElementById('notifMenu');


// Get notification count id
let spanElement = document.getElementById("NotifCount");

let currenNumber=0;
let nuewNumber=0;
function addNotif(content){
    // Get the current number value inside the span
   currentNumber = parseInt(spanElement.innerText);

// Increase the number by 1
    newNumber = currentNumber + 1;

// Update the span's text content with the new number
    spanElement.innerText = newNumber;
    notif = "<div className=\"notification-menu-item\"> <p>"+content+"</p> </div>"
    x.innerHTML += notif;
}