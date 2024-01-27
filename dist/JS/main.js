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
  for (var ele of title) {
    ele.style.display === "none"
      ? (ele.style.display = "inline-block")
      : (ele.style.display = "none");
    // ele.style.display= "none";
  }
};

function showMessage(message, type) {
  var messageDiv = document.getElementById("message");

  // Create a new paragraph element for the message
  var messageParagraph = document.createElement("p");
  messageParagraph.textContent = message;

  // Clear existing content and append the new paragraph
  messageDiv.innerHTML = "";
  messageDiv.appendChild(messageParagraph);

  // Create a new button element
  var closeButton = document.createElement("button");
  closeButton.innerHTML = '<ion-icon name="close-outline"></ion-icon>';
  closeButton.onclick = hideMessage; // Set the onclick event for the button

  // Append the button to the messageDiv
  messageDiv.appendChild(closeButton);

  // Set the display style and apply the appropriate class
  messageDiv.style.display = "block";

  if (type === "success") {
    messageDiv.classList.add("success");
  } else if (type === "error") {
    messageDiv.classList.add("error");
  }
}

function hideMessage() {
  var messageDiv = document.getElementById("message");
  messageDiv.style.display = "none";
}


