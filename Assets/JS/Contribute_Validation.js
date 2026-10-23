document.addEventListener("DOMContentLoaded", function () {
    // Get all form elements
    const form = document.querySelector("form");
    const fullName = document.querySelector('input[name="fullName"]');
    const phoneNumber = document.querySelector('input[name="phoneNumber"]');
    const email = document.querySelector('input[name="email"]');
    const title = document.querySelector('input[name="conTitle"]');
    const options = document.querySelector('select[name="contype"]');
    const article = document.querySelector('textarea[name="conDesc"]');
    const fileUpload = document.querySelector('input[type="file"]');
    
    // Get all message elements
    const nameMessage = document.getElementById("nameMessage");
    const phoneMessage = document.getElementById("phoneMessage");
    const emailMessage = document.getElementById("emailMessage");
    const titleMessage = document.getElementById("titleMessage");
    const optionsMessage = document.getElementById("optionsMessage");
    const descriptionMessage = document.getElementById("descriptionMessage");
    const fileUploadMessage = document.getElementById("fileUploadMessage");

    // Validation Functions
    function validateFullName() {
        if (fullName.value.trim() === "") {
            nameMessage.textContent = "Full name is required.";
            nameMessage.style.color = "red";
            return false;
        }
        nameMessage.textContent = "";
        return true;
    }

    function validatePhoneNumber() {
        const phoneRegex = /^[0-9]{10}$/;
        if (!phoneRegex.test(phoneNumber.value.trim())) {
            phoneMessage.textContent = "Phone number must be 10 digits.";
            phoneMessage.style.color = "red";
            return false;
        }
        phoneMessage.textContent = "";
        return true;
    }

    function validateEmail() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            emailMessage.textContent = "Enter a valid email address.";
            emailMessage.style.color = "red";
            return false;
        }
        emailMessage.textContent = "";
        return true;
    }

    function validateTitle() {
        if (title.value.trim() === "") {
            titleMessage.textContent = "Title is required.";
            titleMessage.style.color = "red";
            return false;
        }
        titleMessage.textContent = "";
        return true;
    }

    function validateOptions() {
        const selectedOption = options.value;
        let valid = true;

        // Reset all messages first
        descriptionMessage.textContent = "";
        fileUploadMessage.textContent = "";
        optionsMessage.textContent = "";

        // Show/hide fields based on selection
        switch (selectedOption) {
            case "Article":
                // Show article, hide file upload
                article.style.display = "block";
                if (article.value.trim() === "") {
                    descriptionMessage.textContent = "Article is required.";
                    descriptionMessage.style.color = "red";
                    valid = false;
                }
                break;

            case "Visual":
                // Show file upload, hide article
                if (fileUpload.value.trim() === "") {
                    fileUploadMessage.textContent = "File upload is required.";
                    fileUploadMessage.style.color = "red";
                    valid = false;
                }
                break;

            case "Both":
                // Show both article and file upload
                article.style.display = "block";
                if (article.value.trim() === "") {
                    descriptionMessage.textContent = "Article is required.";
                    descriptionMessage.style.color = "red";
                    valid = false;
                }
                if (fileUpload.value.trim() === "") {
                    fileUploadMessage.textContent = "File upload is required.";
                    fileUploadMessage.style.color = "red";
                    valid = false;
                }
                break;

            default:
                optionsMessage.textContent = "Please select a contribution type.";
                optionsMessage.style.color = "red";
                valid = false;
        }

        return valid;
    }

    // File Upload Validation
    function validateFileUpload() {
        if (fileUpload.files.length > 0) {
            const file = fileUpload.files[0];
            const fileType = file.type;
            const fileSize = file.size;
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes

            // Check file type
            if (!['image/jpeg', 'image/png', 'image/gif'].includes(fileType)) {
                fileUploadMessage.textContent = "Only JPG, PNG, and GIF images are allowed.";
                fileUploadMessage.style.color = "red";
                fileUpload.value = ''; // Clear the file input
                return false;
            }

            // Check file size
            if (fileSize > maxSize) {
                fileUploadMessage.textContent = "File size exceeds the allowed 5MB limit.";
                fileUploadMessage.style.color = "red";
                fileUpload.value = ''; // Clear the file input
                return false;
            }

            fileUploadMessage.textContent = "";
            return true;
        }
        return true; // Return true if no file is selected (validation will be handled by validateOptions if required)
    }

    // Complete Form Validation
    function validateForm() {
        const isValid =
            validateFullName() &&
            validatePhoneNumber() &&
            validateEmail() &&
            validateTitle() &&
            validateOptions() &&
            validateFileUpload();

        return isValid;
    }

    // Event Listeners
    form.addEventListener("submit", function (e) {
        if (!validateForm()) {
            e.preventDefault(); // Prevent form submission if validation fails
            return;
        }
        // Form will submit naturally to PHP if validation passes
    });

    // Real-time validation event listeners
    options.addEventListener("change", function() {
        validateOptions();
    });

    fullName.addEventListener("input", validateFullName);
    phoneNumber.addEventListener("input", validatePhoneNumber);
    email.addEventListener("input", validateEmail);
    title.addEventListener("input", validateTitle);
    article.addEventListener("input", validateOptions);
    fileUpload.addEventListener("change", function() {
        validateFileUpload();
        validateOptions();
    });

    // Initial validation check
    validateOptions();
});