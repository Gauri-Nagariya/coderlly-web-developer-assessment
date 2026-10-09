// to make nav open and close on click
const menuToggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".nav");


menuToggle.addEventListener("click", function () {
  const isOpen = nav.classList.toggle("is-open");

  menuToggle.setAttribute("aria-expanded", isOpen);
});

// to close nav bar when nav option is clicked
const navLinks = document.querySelectorAll(".nav a");


navLinks.forEach(function (link) {
  link.addEventListener("click", function () {
    nav.classList.remove("is-open");


    menuToggle.setAttribute("aria-expanded", "false");
  });
});

// to close nav when escape key is pressed
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    nav.classList.remove("is-open");
    menuToggle.setAttribute("aria-expanded", "false");
  }
});

// FAQ logic
const faqButtons = document.querySelectorAll(".faq_question");

faqButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    const answerId = button.getAttribute("aria-controls");
    const answer = document.getElementById(answerId);

    const isOpen = button.getAttribute("aria-expanded") === "true";

    button.setAttribute("aria-expanded", !isOpen);
    answer.hidden = isOpen;
  });
});

// form logic
const form = document.querySelector("#contact-form");

const nameInput = document.querySelector("#name");
const emailInput = document.querySelector("#email");
const messageInput = document.querySelector("#message");

const nameError = document.querySelector("#name-error");
const emailError = document.querySelector("#email-error");
const messageError = document.querySelector("#message-error");

const successMessage = document.querySelector("#form-success");

form.addEventListener("submit", function (event) {
  event.preventDefault();

  let isValid = true;
  successMessage.hidden = true;
  successMessage.textContent = "";

  nameError.textContent = "";
  emailError.textContent = "";
  messageError.textContent = "";

  nameInput.classList.remove("isInvalid");
  emailInput.classList.remove("isInvalid");
  messageInput.classList.remove("isInvalid");

  if (nameInput.value.trim() === "") {
    nameError.textContent = "Please enter your name.";
    nameInput.classList.add("isInvalid");
    isValid = false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const email = emailInput.value.trim();
  if (email === "") {
    emailError.textContent = "Please enter your email.";
    emailInput.classList.add("isInvalid");
    isValid = false;
  } else if (!emailPattern.test(email)) {
    emailError.textContent = "Please enter a valid email.";
    emailInput.classList.add("isInvalid");
    isValid = false;
  }

  if (messageInput.value.trim() === "") {
    messageError.textContent = "Please enter a message.";
    messageInput.classList.add("isInvalid");
    isValid = false;
  }

  if (!isValid) {
    if (nameInput.classList.contains("isInvalid")) {
      nameInput.focus();
    } else if (emailInput.classList.contains("isInvalid")) {
      emailInput.focus();
    } else if (messageInput.classList.contains("isInvalid")) {
      messageInput.focus();
    }

    return;
  }

  successMessage.textContent =
    "Thanks! Your message has been submitted successfully.";

  successMessage.hidden = false;

  form.reset();
});
