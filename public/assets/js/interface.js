
/*fade in and fade out*/


// Get all the fade-row elements
var fadeRows = document.querySelectorAll('.fade-row');

// Function to check if an element is in the viewport
function isInViewport(element) {
    var rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Function to handle the scroll event
function handleScroll() {
    fadeRows.forEach(function(row) {

        if (isInViewport(row)) {
            row.style.opacity= "1";
        } else {
            row.style.opacity= "0";
        }
    });
}

function handleScroll_t() {
    doc=document.querySelector(".text-center");
    if (isInViewport(doc)) {
        doc.style.opacity= "1";
    } else {
        doc.style.opacity= "0";
    }

}

// Add scroll event listener
window.addEventListener('scroll', handleScroll);
window.addEventListener('scroll', handleScroll_t);

// Trigger the initial check when the page loads
handleScroll();

handleScroll_t();