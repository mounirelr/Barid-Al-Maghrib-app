
let activePage = window.location.href;
console.log(activePage);
activePage = activePage.slice(23);

console.log(activePage);
if(activePage.includes('.php')){
    activePage=activePage.slice(0,activePage.indexOf('.php'))
}
if(activePage.includes('/')){
    console.log(activePage.indexOf('/'));
activePage = activePage.slice(0,activePage.indexOf('/'))
}
console.log(activePage);

links = document.querySelectorAll('li span');

for(let i=0;i<links.length;i++){
    if(links[i].parentElement.parentElement.classList.contains("active"))
        links[i].parentElement.parentElement.classList.remove("active");

    if(links[i].innerHTML==activePage || (links[i].innerHTML=="Aperçu" && activePage=="apercu") || (links[i].innerHTML=="paramètres" && activePage=="parametre") )
        links[i].parentElement.parentElement.classList.add("active");

    
}




