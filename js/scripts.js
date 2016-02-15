var logoClick = document.getElementById("logo");
var homeButton = document.getElementById("home");
var poemButton = document.getElementById("poem");
var filmButton = document.getElementById("film");
var contactButton = document.getElementById("contact");

logoClick.addEventListener('click', function() {
    homeButton.setAttribute("class", "float-right header-links-selected");
    poemButton.setAttribute("class", "float-right header-links");
    filmButton.setAttribute("class", "float-right header-links");
    contactButton.setAttribute("class", "float-right header-links");
})

homeButton.addEventListener('click', function() {
    headerLinkUpdate("home");
})

poemButton.addEventListener('click', function() {
    headerLinkUpdate("poem");
})

filmButton.addEventListener('click', function() {
    headerLinkUpdate("film");
})

contactButton.addEventListener('click', function() {
    headerLinkUpdate("contact");
})

function headerLinkUpdate(headerClick) {
    if (headerClick == "home") homeButton.setAttribute("class", "float-right header-links-selected");
    else homeButton.setAttribute("class", "float-right header-links");
    if (headerClick == "poem") poemButton.setAttribute("class", "float-right header-links-selected");
    else poemButton.setAttribute("class", "float-right header-links");
    if (headerClick == "film") filmButton.setAttribute("class", "float-right header-links-selected");
    else filmButton.setAttribute("class", "float-right header-links");
    if (headerClick == "contact") contactButton.setAttribute("class", "float-right header-links-selected");
    else contactButton.setAttribute("class", "float-right header-links");
}

console.log("armed and ready");