document.querySelector("#hamburger-click").addEventListener('click', event => {
    event.preventDefault();
    document.querySelector("#hamburger-click").classList.add("hidden");
    document.querySelector("#nav-box").classList.remove("hidden");
});

window.addEventListener('scroll',(event) => {
    if(document.querySelector("#hamburger-click").classList.contains("hidden")) {
        document.querySelector("#hamburger-click").classList.remove("hidden");
        document.querySelector("#nav-box").classList.add("hidden");
    }
})