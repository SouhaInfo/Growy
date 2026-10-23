document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form");

    // Validation functions
    function validateName(id, fieldName) {
        const input = document.getElementById(id);
        const errorDiv = input.nextElementSibling;
        const namePattern = /^[a-zA-Z\s]+$/;
        if (input.value.trim() === "") {
            errorDiv.textContent = `${fieldName} is required.`;
            input.style.borderColor = "red";
            return false;
        }
        if (!namePattern.test(input.value.trim())) {
            errorDiv.textContent = `${fieldName} must contain only letters.`;
            input.style.borderColor = "red";
            return false;
        }
        errorDiv.textContent = "";
        input.style.borderColor = "green";
        return true;
    }

    function validatePhoneNumber(id) {
        const input = document.getElementById(id);
        const errorDiv = input.nextElementSibling;
        const phonePattern = /^([0]{1}[5-7]{1}[0-9]{8})$/;
        if (input.value.trim() === "") {
            errorDiv.textContent = "Phone number is required.";
            input.style.borderColor = "red";
            return false;
        }
        if (!phonePattern.test(input.value.trim())) {
            errorDiv.textContent = "Invalid phone number, please enter a valid number.";
            input.style.borderColor = "red";
            return false;
        }
        errorDiv.textContent = "";
        input.style.borderColor = "green";
        return true;
    }

    function validateEmail(id) {
        const input = document.getElementById(id);
        const errorDiv = input.nextElementSibling;
        const emailPattern = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        if (input.value.trim() === "") {
            errorDiv.textContent = "Email is required.";
            input.style.borderColor = "red";
            return false;
        }
        if (!emailPattern.test(input.value.trim())) {
            errorDiv.textContent = "Please enter a valid email address.";
            input.style.borderColor = "red";
            return false;
        }
        errorDiv.textContent = "";
        input.style.borderColor = "green";
        return true;
    }

    function validateCardNumber(id) {
        const input = document.getElementById(id);
        const errorDiv = input.nextElementSibling;
        const cardPattern = /^\d{16}$/;
        if (input.value.trim() === "") {
            errorDiv.textContent = "Card number is required.";
            input.style.borderColor = "red";
            return false;
        }
        if (!cardPattern.test(input.value.trim())) {
            errorDiv.textContent = "Card number must be exactly 16 digits.";
            input.style.borderColor = "red";
            return false;
        }
        errorDiv.textContent = "";
        input.style.borderColor = "green";
        return true;
    }

    function validateCVV(id) {
        const input = document.getElementById(id);
        const errorDiv = input.nextElementSibling;
        if (input.value.trim() === "") {
            errorDiv.textContent = "CVV is required.";
            input.style.borderColor = "red";
            return false;
        }
        if (!/^\d{3}$/.test(input.value.trim())) {
            errorDiv.textContent = "CVV must be exactly 3 digits.";
            input.style.borderColor = "red";
            return false;
        }
        errorDiv.textContent = "";
        input.style.borderColor = "green";
        return true;
    }

    function validateExpirationDate(monthId, yearId) {
        const monthInput = document.getElementById(monthId);
        const yearInput = document.getElementById(yearId);
        const month = parseInt(monthInput.value, 10); // Get the entered month as an integer
        const year = parseInt(yearInput.value, 10);  // Get the entered year as an integer
        const errorDiv = monthInput.nextElementSibling;
    
        // Check if inputs are valid numbers
        if (isNaN(month) || isNaN(year) || month < 1 || month > 12) {
            errorDiv.textContent = "Expiration date is invalid.";
            monthInput.style.borderColor = "red";
            yearInput.style.borderColor = "red";
            return false;
        }
    
        // Get the current date
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1; // Months are 0-based in JS
        const currentYear = currentDate.getFullYear();
    
        // Check if the card is expired
        if (year < currentYear || (year === currentYear && month <= currentMonth)) {
            errorDiv.textContent = "Card already expired.";
            yearInput.style.borderColor = "red";
            return false;
        }
    
        // Clear error if the date is valid
        errorDiv.textContent = "";
        yearInput.style.borderColor = "green";
        return true;
    }
    
    // Set up validation on input events for real-time feedback
    const inputs = [
        { id: "firstname", validator: () => validateName("firstname", "Firstname") },
        { id: "surname", validator: () => validateName("surname", "Surname") },
        { id: "phonenum", validator: () => validatePhoneNumber("phonenum") },
        { id: "email", validator: () => validateEmail("email") },
        { id: "CardNum", validator: () => validateCardNumber("CardNum") },
        { id: "cvv", validator: () => validateCVV("cvv") },
        {
            id: "expMonth",
            validator: () => validateExpirationDate("expMonth", "expYear"),
        },
    ];

    inputs.forEach(({ id, validator }) => {
        const input = document.getElementById(id);
        input.addEventListener("blur", validator); // Validate on blur
        input.addEventListener("input", validator); // Validate on input for real-time feedback
    });

    // Submit event listener with validation and success modal display
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Execute validation functions
        const isValid = inputs.every(({ validator }) => validator());

        // If all fields are valid, show the success message
        if (isValid) {
            const successModal = document.querySelector(".success-modal");
            successModal.style.display = "flex";

            // Clear form fields after successful submission
            form.reset();
            document.querySelectorAll("input").forEach(input => {
                input.style.borderColor = "#ccc"; // Reset input border color
            });

            // Hide the modal after 3 seconds
            setTimeout(() => {
                successModal.style.display = "none";
            }, 3000);

            console.log("Form submitted successfully");
        }
    });
});
