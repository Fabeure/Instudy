
const input = document.getElementById("InputNameSearch");
let request = null
// Add an event listener that listens for changes in the input text field
input.addEventListener("input",
    (event) => {
        event.preventDefault();

        const query = input.value;
        event.preventDefault(); // prevent default behavior of creating a new line
        request = $.ajax({
            url: `/hub/search`,
            type: 'POST',
            data: {
                query: query
            }
        })
    });


function  add(users){
    let ProfileList = document.getElementById("ProfileList") ;
    let link="";
    let list = [];
    let element = "";
    for (let i=0; i<users.length; i++){
        link = `profile\/${users[i]}`
        element = "<tr> <td id=\"profileName\" style=\"text-align: center;\"> <a href= \" " + link + "\">"+users[i]+" </a> </td> <td> </td> </tr>"
        list+=element;
    }
    ProfileList.innerHTML = list;
}