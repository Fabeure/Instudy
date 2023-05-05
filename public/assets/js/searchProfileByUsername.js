// Get references to the input text field and the display element
const input = document.getElementById("InputNameSearch");
const display = document.getElementById("profileName");
const ProfileList = document.getElementById("ProfileList") ;
let users = "{{users}}";
// Add an event listener that listens for changes in the input text field
input.addEventListener("input",
    () => {
        const searchQuery = input.value;
        let request = null;

        display.textContent = searchQuery;
        request = $.ajax({
            url: `/search`,
            type: 'POST',
            data: {
                searchQuery: searchQuery
            }
        });
        Promise.all(request).then(r => console.log("success"));
    }
    );