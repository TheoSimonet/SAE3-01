const left = document.querySelector(".left");
const burger__item = document.querySelector("#burger__icon");

left.addEventListener('mouseenter', evt => show())
left.addEventListener('mouseleave', evt => show())

function show() {
    left.classList.contains("active") ? close() : open();
}

function open() {

    burger__item.classList.add("bx-x")
    burger__item.classList.remove("bx-menu")

    left.classList.add("active")

}

function close() {

    burger__item.classList.remove("bx-x")
    burger__item.classList.add("bx-menu")

    left.classList.remove("active")

}