$(".nav li:first").addClass("active");

var showSection = function showSection(section, isAnimate) {
        var direction = section.replace(/#/, ""),
                reqSection = $(".section").filter(
                        '[data-section="' + direction + '"]'
                ),
                reqSectionPos = reqSection.offset().top - 0;

        if (isAnimate) {
                $("body, html").animate(
                        {
                                scrollTop: reqSectionPos,
                        },
                        800
                );
        } else {
                $("body, html").scrollTop(reqSectionPos);
        }
};
var checkSection = function checkSection() {
        $(".section").each(function () {
                var $this = $(this),
                        topEdge = $this.offset().top - 80,
                        bottomEdge = topEdge + $this.height(),
                        wScroll = $(window).scrollTop();
                if (topEdge < wScroll && bottomEdge > wScroll) {
                        var currentId = $this.data("section"),
                                reqLink = $("a").filter(
                                        "[href*=\\#" + currentId + "]"
                                );
                        reqLink.closest("li").siblings().removeClass("active");
                }
        });
};

$(".main-menu, .scroll-to-section").on(
        "click",
        "a:not([href='/register'])",
        function (e) {
                if ($(e.target).hasClass("external")) {
                        return;
                }
                e.preventDefault();
                $("#menu").removeClass("active");
                showSection($(this).attr("href"), true);
        }
);

$(window).scroll(function () {
        checkSection();
});


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