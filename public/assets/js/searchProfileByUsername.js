
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

    const ProfileList = document.getElementById("ProfileList") ;
    let list = [];
    let element = "";
    for (let i=0; i<users.length; i++){
        element = "<tr> <td> <img src=\"{{ asset(\'assets\images\profileDefaultPhoto.jpg\') }}\" alt=\"Profile Image\"className=\"rounded-circle shadow rounded-lg d-block d-sm-flex\" width=\"40\" height=\"40\"\> </td> <td id=\"profileName\">"+ users[i]+"</td> <td> <button className=\"btn btn-outline-info\"onClick=\"location.href=\'{{ path(\'app_profile\',{\'username\' : app.user.username }) }}\';\"> <svg xmlns=\"http:\\www.w3.org\2000\svg\" width=\"16\" height=\"16\" fill=\"currentColor\"className=\"bi bi-eye\" viewBox=\"0 0 16 16\"> <pathd=\"M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z\"/> <pathd=\"M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z\"/> </svg>visit profile </button> </td> </tr>"
        list+=element;
    }
    ProfileList.innerHTML = list;
}