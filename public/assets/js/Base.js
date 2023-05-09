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

//get the section element (important for choosing animation)
let section = document.getElementById('hero')

//get different kinds of animations
let classNames = ['slider-thumb1', 'slider-thumb2', 'slider-thumb3','slider-thumb4','slider-thumb5'];

//get a random animation
let randomIndex = Math.floor(Math.random() * classNames.length);
let randomClass = classNames[randomIndex];


//add animation
section.classList.add('py-5', 'my-5', randomClass)
