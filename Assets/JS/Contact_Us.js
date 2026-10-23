document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#contact-form");
    const nameInput = form.querySelector('#name');
    const phoneInput = form.querySelector('#phone');
    const emailInput = form.querySelector('#email');
    const optionsInput = form.querySelector('#options');
    const descriptionInput = form.querySelector('#description');

    const nameMessage = document.querySelector("#nameMessage span");
    const phoneMessage = document.querySelector("#phoneMessage span");
    const emailMessage = document.querySelector("#emailMessage span");
    const optionsMessage = document.querySelector("#optionsMessage span");
    const descriptionMessage = document.querySelector("#descriptionMessage span");

    // Validation Functions
    function validateName() {
        const nameValue = nameInput.value.trim();
        if (!/^[A-Za-z\s]+$/.test(nameValue) || nameValue === "") {
            nameInput.style.borderColor = "red";
            nameMessage.textContent = "*Invalid name format/ not entered";
            nameMessage.classList.remove("success");
            nameMessage.classList.add("error");
            return false;
        }
        nameInput.style.borderColor = "green";
        nameMessage.textContent = "";
        nameMessage.classList.remove("error");
        nameMessage.classList.add("success");
        return true;
    }

    function validatePhone() {
        const phoneValue = phoneInput.value.trim();
        if (!/^0\d{9}$/.test(phoneValue)) {
            phoneInput.style.borderColor = "red";
            phoneMessage.textContent = "*Invalid phone number format/ not entered.";
            phoneMessage.classList.remove("success");
            phoneMessage.classList.add("error");
            return false;
        }
        phoneInput.style.borderColor = "green";
        phoneMessage.textContent = "";
        phoneMessage.classList.remove("error");
        phoneMessage.classList.add("success");
        return true;
    }

    function validateEmail() {
        const emailValue = emailInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailValue)) {
            emailInput.style.borderColor = "red";
            emailMessage.textContent = "*Invalid email address.";
            emailMessage.classList.remove("success");
            emailMessage.classList.add("error");
            return false;
        }
        emailInput.style.borderColor = "green";
        emailMessage.textContent = "";
        emailMessage.classList.remove("error");
        emailMessage.classList.add("success");
        return true;
    }

    function validateOptions() {
        if (optionsInput.value === "") {
            optionsInput.style.borderColor = "red";
            optionsMessage.textContent = "*Please choose an option.";
            optionsMessage.classList.remove("success");
            optionsMessage.classList.add("error");
            return false;
        }
        optionsInput.style.borderColor = "green";
        optionsMessage.textContent = "";
        optionsMessage.classList.remove("error");
        optionsMessage.classList.add("success");
        return true;
    }

    function validateDescription() {
        const descriptionValue = descriptionInput.value.trim();
        if (descriptionValue === "") {
            descriptionInput.style.borderColor = "red";
            descriptionMessage.textContent = "*Description cannot be empty.";
            descriptionMessage.classList.remove("success");
            descriptionMessage.classList.add("error");
            return false;
        }
        descriptionInput.style.borderColor = "green";
        descriptionMessage.textContent = "";
        descriptionMessage.classList.remove("error");
        descriptionMessage.classList.add("success");
        return true;
    }

    // Real-time Validation Event Listeners
    nameInput.addEventListener("input", validateName);
    phoneInput.addEventListener("input", validatePhone);
    emailInput.addEventListener("input", validateEmail);
    optionsInput.addEventListener("change", validateOptions);
    descriptionInput.addEventListener("input", validateDescription);

    // Form Submission Validation
    form.addEventListener("submit", function (event) {
        const isNameValid = validateName();
        const isPhoneValid = validatePhone();
        const isEmailValid = validateEmail();
        const isOptionsValid = validateOptions();
        const isDescriptionValid = validateDescription();

        if (!isNameValid || !isPhoneValid || !isEmailValid || !isOptionsValid || !isDescriptionValid) {
            event.preventDefault(); // Prevent form submission if any field is invalid
        }
    });
});
