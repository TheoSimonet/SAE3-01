const faq__item = document.querySelectorAll('.faq__item');

faq__item.forEach(item => {
    item.addEventListener('click', evt => {

        // Permet de désactiver toutes les autres FAQ ouvertes
        disableAll()

        // Permet d'afficher la question
        item.querySelector(".faq__reponse").classList.toggle("hide")

        // Permet de clorer la box
        item.style.backgroundColor = "var(--primary)";
        item.style.color = "var(--white)";

        // Permet de mettre à jour la flèche
        item.querySelector(".faq__question").querySelector(".bx").classList.add("bxs-down-arrow");
        item.querySelector(".faq__question").querySelector(".bx").classList.remove("bxs-right-arrow");

    })
})

function disableAll() {

    faq__item.forEach(item => {

        // Applique la classe permettant de cacher l'item si celui-ci est visible
        if (!item.classList.contains("hide"))
            item.querySelector(".faq__reponse").classList.add("hide")

        // Permet de colorer la box en noir
        item.style.backgroundColor = "var(--black)";

        // Permet de mettre à jour la flèche
        item.querySelector(".faq__question").querySelector(".bx").classList.remove("bxs-down-arrow");
        item.querySelector(".faq__question").querySelector(".bx").classList.add("bxs-right-arrow");

    })
}