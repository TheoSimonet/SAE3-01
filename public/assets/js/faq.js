const faq__item = document.querySelectorAll('.faq__item');

faq__item.forEach(item => {
    item.addEventListener('click', evt => {

        const faq__reponse = item.querySelector(".faq__reponse");
        const bx__icon = item.querySelector(".bx");

        if (!faq__reponse.classList.contains("hide")) {
            disableAll()
            return;
        }

        // Permet de désactiver toutes les autres FAQ ouvertes
        disableAll()

        // Permet d'afficher la question
        faq__reponse.classList.toggle("hide")

        // Permet de clorer la box
        item.style.backgroundColor = "var(--primary)";
        item.style.color = "var(--white)";

        // Permet de mettre à jour la flèche
        bx__icon.classList.add("bxs-down-arrow");
        bx__icon.classList.remove("bxs-right-arrow");

    })
})

function disableAll() {
    faq__item.forEach(item => {

        const faq__reponse = item.querySelector(".faq__reponse");
        const bx__icon = item.querySelector(".bx");

        // Applique la classe permettant de cacher l'item si celui-ci est visible
        if (!item.classList.contains("hide"))
            faq__reponse.classList.add("hide")

        // Permet de colorer la box en noir
        item.style.backgroundColor = "var(--black)";

        // Permet de mettre à jour la flèche
        bx__icon.classList.remove("bxs-down-arrow");
        bx__icon.classList.add("bxs-right-arrow");

    })
}