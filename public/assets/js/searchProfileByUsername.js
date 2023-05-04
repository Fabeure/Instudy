// Get references to the input text field and the display element
const input = document.getElementById("InputNameSearch");
const display = document.getElementById("profileName");
const ProfileList = document.getElementById("ProfileList") ;
// Add an event listener that listens for changes in the input text field
input.addEventListener("input", () => {
    // Update the text content of the display element to match the input text

    $.ajax({
        url: "{{ path('search_profile') }}",
        method: "POST",
        data: { query: input.value },
        success: function(result) {
            var profiles = JSON.parse(result);
            for( var profile in profiles ){
                add(profile , ProfileList);
            }
        },
        error: function(error) {
            alert("lelelee");
        }
    });
    display.textContent = input.value;
});

function add(text , place ){
    let message= " <li class=\"list-group-item\">"+text+"</li>" ;
    place.innerHTML += message;
}