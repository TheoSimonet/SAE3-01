document.querySelector("#search__bar").addEventListener('keyup', update)

function update() {

    let input, filter, section, items, a, i, txtValue;

    input = document.getElementById('search__bar');
    filter = input.value.toUpperCase();
    section = document.querySelector(".projet__bottom");
    items = section.querySelectorAll(".projet__item");

    for (i = 0; i < items.length; i++) {
        a = items[i].querySelector(".projet__item__titre");
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }

}