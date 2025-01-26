import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const button = document.getElementById("hamburger-icon");
const nav = document.getElementById("mobile-menu");

//po kliknieciu w hamburger-icon pokazuja sie dane, po kolejnym kliknieciu znikaja
button.addEventListener("click", ()=>{
    nav.classList.toggle(("flex"));
    nav.classList.toggle(("hidden"));
})
