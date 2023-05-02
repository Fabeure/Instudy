const searchButton = document.getElementById('searchButton');

searchButton.addEventListener('click',function (){
    const name = prompt("What's your name?");
    const length = name.length;

    document.getElementById('user-list').innerHTML = length;
    }
);