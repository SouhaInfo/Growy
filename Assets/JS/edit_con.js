document.addEventListener("DOMContentLoaded", function () {
    // Get the form and elements by ID
    const form = document.getElementById("edit");
    const title = document.getElementById("title");
    const type = document.getElementById("type");
    const description = document.getElementById("description");
    const titleMessage = document.getElementById("titleMessage");
    const typeMessage = document.getElementById("typeMessage");
    const descriptionMessage = document.getElementById("descriptionMessage");

    // Validate Title field
    function validateTitle() {
        if (title.value.trim() === "") {
            titleMessage.textContent = "Title is required.";
            titleMessage.style.color = "red";
            return false;
        }
        titleMessage.textContent = ""; // Clear the message if valid
        return true;
    }

    // Validate Type field
    function validateType() {
        if (type.value.trim() === "") {
            typeMessage.textContent = "Type is required.";
            typeMessage.style.color = "red";
            return false;
        }
        typeMessage.textContent = ""; // Clear the message if valid
        return true;
    }

    // Validate Description field
    function validateDescription() {
        if (description.value.trim() === "") {
            descriptionMessage.textContent = "Description is required.";
            descriptionMessage.style.color = "red";
            return false;
        }
        descriptionMessage.textContent = ""; // Clear the message if valid
        return true;
    }

    // Real-time validation on input events
    title.addEventListener("input", validateTitle);
    type.addEventListener("input", validateType);
    description.addEventListener("input", validateDescription);

    // Validate the form on submission
    form.addEventListener("submit", function (e) {
        const isValid =
            validateTitle() &&
            validateType() &&
            validateDescription();

        if (!isValid) {
            e.preventDefault(); // Prevent form submission if invalid
        }
    });
});
