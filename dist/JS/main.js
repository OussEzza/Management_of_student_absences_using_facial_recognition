// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li.item");

function activeLink() {
    list.forEach((item) => {
        item.classList.remove("hovered");
    });
    this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
    navigation.classList.toggle("active");
    main.classList.toggle("active");
    let title = document.getElementsByClassName("title");
    // console.log(title);
    for(var ele of title){
        (ele.style.display === "none") ? ele.style.display = "inline-block" : ele.style.display = "none";
        // ele.style.display= "none";
    }   
};