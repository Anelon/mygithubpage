var video = [
    '<div class="video"><h4>Time Splice</h4><iframe width="480" height="360" src="//www.youtube-nocookie.com/embed/591EtlZhyaU?rel=0" frameborder="0" allowfullscreen></iframe><br><p>Me and my friend Wildman decided it would be fun to write, film, and edit this project really fast, in two weeks to be exact, this is what came out. He did the filming, supplied the music, and consulted the editing along with a cameo in the video at the end. I was the main actor and editor.</p></div>', 
    '<div class="video"><h4>Guys and Dolls</h4><iframe width="480" height="360" src="//www.youtube-nocookie.com/embed/ddpsYcnsR6E?rel=0" frameborder="0" allowfullscreen></iframe><br><p>This was a video scavanger hunt project, we had to get a bunch of perticular shots, the hardest part of this project like all of my projects was thinking of an idea on what to do for it, luckily though I have my lovely assistant (the female actor) who is a writer who came up with the story concept.</p></div>',
    '<div class="video"><h4>That Rabbits Dynamite</h4><iframe width="480" height="360" src="//www.youtube-nocookie.com/embed/X-q1Rst1Hn0?rel=0" frameborder="0" allowfullscreen></iframe><br><p>This was my first encounter with kenetic typography. I really liked it and continued on with another to a full song instead of just a short movie quote.</p></div>',
    '<div class="video"><h4>Dyslexia PSA</h4><iframe width="480" height="360" src="//www.youtube-nocookie.com/embed/HI2pN3uiewk?rel=0" frameborder="0" allowfullscreen></iframe><br><p>This is a public service announcement for video production, and after my first encounter I decided to strike again with more typography. The script was writen by my lovely assistant (she is amazing). I set up the typograpy with lots of good fun. </p></div>'
    ];


var logoClick = document.getElementById("logo");
var homeButton = document.getElementById("home");
var poemButton = document.getElementById("poem");
var filmButton = document.getElementById("film");
var contactButton = document.getElementById("contact");
var bodyText = document.getElementById("bodyText");

logoClick.addEventListener('click', function() {
    headerLinkUpdate("home");
    bodyUpdate("home");
})

homeButton.addEventListener('click', function() {
    headerLinkUpdate("home");
    bodyUpdate("home");
})

poemButton.addEventListener('click', function() {
    headerLinkUpdate("poem");
    bodyUpdate("poem");
})

filmButton.addEventListener('click', function() {
    headerLinkUpdate("film");
    bodyUpdate("film");
})

contactButton.addEventListener('click', function() {
    headerLinkUpdate("contact");
    bodyUpdate("contact");
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

function bodyUpdate(headerClick) {
    var section = bodyText.querySelectorAll("div");
        console.log(section);
        console.log(section.length);
    bodyText.innerHTML = "";
    switch (headerClick) {
        case "poem":
            
            break;
        case "film":
            var print = ""
            for (var i = 0; i < video.length; i++) {
                print += video[i];
            }
            bodyText.innerHTML = print;
            break;
        case "contact":
            
            break;
        default:
    }
    
}

console.log("armed and ready");