const list = document.querySelector('user-search-form');
// const userList = document.getElementById('user-list');
const userList = document.querySelector("#user-list");

//searchUserName

const myButton = document.getElementById('searchButton');
myButton.addEventListener('click', handleClick);
list.addEventListener('submit', (event ) => {
    event.preventDefault();

    const username = document.getElementById('username').value;

    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                displayUsers(response);
            } else {
                console.error('Failed to fetch user list');
            }
        }
    }

    xhr.open('GET', `/user/list?username=${username}`);
    xhr.send();
});

function displayUsers(users) {
    userList.innerHTML = '';

    if (users.length === 0) {
        userList.innerHTML = '<p>No users found.</p>';
        return;
    }

    const table = document.createElement('table');
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');
    const tr = document.createElement('tr');
    const thId = document.createElement('th');
    const thUsername = document.createElement('th');
    const thEmail = document.createElement('th');

    thId.textContent = 'ID';
    thUsername.textContent = 'Username';
    thEmail.textContent = 'Email';

    tr.appendChild(thId);
    tr.appendChild(thUsername);
    tr.appendChild(thEmail);

    thead.appendChild(tr);

    table.appendChild(thead);
    table.appendChild(tbody);

    users.forEach((user) => {
        const tr = document.createElement('tr');
        const tdId = document.createElement('td');
        const tdUsername = document.createElement('td');
        const tdEmail = document.createElement('td');

        tdId.textContent = user.id;
        tdUsername.textContent = user.username;
        tdEmail.textContent = user.email;

        tr.appendChild(tdId);
        tr.appendChild(tdUsername);
        tr.appendChild(tdEmail);

        tbody.appendChild(tr);
    });

    userList.appendChild(table);
}