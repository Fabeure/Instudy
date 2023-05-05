
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


function addProfile(users) {
    const ProfileList = document.getElementById("ProfileList");
    ProfileList.innerHTML = "";

    for (let i = 0; i < users.length; i++) {
        const element = document.createElement("tr");

        const imageTd = document.createElement("td");
        const image = document.createElement("img");
        image.src = "{{ asset('assets/images/profileDefaultPhoto.jpg') }}";
        image.className = "rounded-circle shadow rounded-lg d-block d-sm-flex";
        image.width = "40";
        image.height = "40";
        imageTd.appendChild(image);
        element.appendChild(imageTd);

        const nameTd = document.createElement("td");
        nameTd.textContent = users[i];
        element.appendChild(nameTd);

        const buttonTd = document.createElement("td");

        const button = document.createElement("button");
        button.addEventListener("click", function() {
            goToProfile(users[i]);
        });
        button.className = "btn btn-outline-info";

        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("width", "16");
        svg.setAttribute("height", "16");
        svg.setAttribute("fill", "currentColor");
        svg["className"] = "bi bi-eye";
        const path1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path1.setAttribute("d", "M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z");
        const path2 = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path2.setAttribute("d", "M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 ");
        const path3 = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path3.setAttribute("d", "M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z");
        svg.appendChild(path1);
        svg.appendChild(path2);
        svg.appendChild(path3);
        button.appendChild(svg);
        button.appendChild(document.createTextNode("visit profile"));
        buttonTd.appendChild(button);
        element.appendChild(buttonTd);

        ProfileList.appendChild(element);
    }
}

function goToProfile(username) {
    const url = "http://127.0.0.1:8000/profile/" + encodeURIComponent(username);
    const updatedUrl = url.replace("''", username);
    window.location.href = updatedUrl;
}
