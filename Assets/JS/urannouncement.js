document.addEventListener("DOMContentLoaded", function () {
    // Fields to validate
    const form = document.getElementById("announcementForm");
    const phoneField = document.getElementById("phone");
    const emailField = document.getElementById("email");
    const priceField = document.getElementById("price");
    const descriptionField = document.getElementById("description");
    const fullNameField = document.getElementById("name");
    const titleField = document.getElementById("title");
    const dateTimeField = document.getElementById("datetime");
    const locationField = document.getElementById("location");
    const typeField = document.getElementById("type");
    const imageField = document.getElementById("fileInput");

    // Error containers
    const phoneError = document.getElementById("phoneError");
    const emailError = document.getElementById("emailError");
    const priceError = document.getElementById("priceError");
    const descriptionError = document.getElementById("descriptionError");
    const fullNameError = document.getElementById("nameError");
    const titleError = document.getElementById("titleError");
    const dateTimeError = document.getElementById("datetimeError");
    const locationError = document.getElementById("locationError");
    const typeError = document.getElementById("typeError");
    const imageError = document.getElementById("fileInputError");

    // Validation functions
    const validatePhone = (value) => /^(05|06|07)[0-9]{8}$/.test(value);
    const validateEmail = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    const validatePrice = (value) => {
        const price = parseFloat(value);
        return !isNaN(price) && price >= 100 && price <= 10000000;
    };
    const validateDescription = (value) => value.trim().length >= 20 && value.trim().length <= 300;
    const validateFullName = (value) => /^[a-zA-Z\s]{3,50}$/.test(value);
    const validateTitle = (value) => value.trim().length >= 5 && value.trim().length <= 100;
    const validateDateTime = (value) => {
        const now = new Date();
        const inputDateTime = new Date(value);
        return inputDateTime >= now;
    };
    const validateLocation = (value) => value.trim() !== "";
    const validateType = (value) => value.trim() !== "";
    const validateImage = (file) => {
        const validTypes = ["image/jpeg", "image/png"];
        const maxSize = 5 * 1024 * 1024; // 5 MB
        const desiredAspectRatios = [
            [2, 1],   // 2:1 aspect ratio
            [16, 9],  // 16:9 aspect ratio
            [4, 3],   // 4:3 aspect ratio
            [1, 2]    // 1:2 aspect ratio
        ];

        if (!file) return Promise.resolve(false);

        const isValidType = validTypes.includes(file.type);
        const isValidSize = file.size <= maxSize;

        return new Promise((resolve) => {
            const img = new Image();
            img.src = URL.createObjectURL(file);

            img.onload = () => {
                const aspectRatio = [img.width, img.height];
                const isValidAspectRatio = desiredAspectRatios.some(([ratioWidth, ratioHeight]) => 
                    Math.abs(aspectRatio[0] / aspectRatio[1] - (ratioWidth / ratioHeight)) < 0.01
                );
                resolve(isValidType && isValidSize && isValidAspectRatio);
            };

            img.onerror = () => resolve(false);
        });
    };

    // Function to check if fields are empty
    const validateFieldEmpty = (value) => value.trim() === "";

    // Field-specific validation handlers
    phoneField.addEventListener("input", () => {
        const value = phoneField.value;
        if (validatePhone(value)) {
            phoneError.style.display = "none";
        } else {
            phoneError.textContent = "Phone number must start with 05, 06, or 07 and be 10 digits.";
            phoneError.style.display = "block";
        }
    });

    emailField.addEventListener("input", () => {
        const value = emailField.value;
        if (validateEmail(value)) {
            emailError.style.display = "none";
        } else {
            emailError.textContent = "Please enter a valid email address.";
            emailError.style.display = "block";
        }
    });

    priceField.addEventListener("input", () => {
        const value = priceField.value;
        if (validatePrice(value)) {
            priceError.style.display = "none";
        } else {
            priceError.textContent = "Price must be between 100 and 10,000,000 DZD.";
            priceError.style.display = "block";
        }
    });

    descriptionField.addEventListener("input", () => {
        const value = descriptionField.value;
        if (validateDescription(value)) {
            descriptionError.style.display = "none";
        } else {
            descriptionError.textContent = "Description must be between 20 and 300 characters.";
            descriptionError.style.display = "block";
        }
    });

    fullNameField.addEventListener("input", () => {
        const value = fullNameField.value;
        if (validateFullName(value)) {
            fullNameError.style.display = "none";
        } else {
            fullNameError.textContent = "Full name must be 3-50 alphabetic characters.";
            fullNameError.style.display = "block";
        }
    });

    titleField.addEventListener("input", () => {
        const value = titleField.value;
        if (validateTitle(value)) {
            titleError.style.display = "none";
        } else {
            titleError.textContent = "Title must be between 5 and 100 characters.";
            titleError.style.display = "block";
        }
    });

    dateTimeField.addEventListener("input", () => {
        const value = dateTimeField.value;
        if (validateDateTime(value)) {
            dateTimeError.style.display = "none";
        } else {
            dateTimeError.textContent = "Date and time must not be in the past.";
            dateTimeError.style.display = "block";
        }
    });

    locationField.addEventListener("change", () => {
        const value = locationField.value;
        if (validateLocation(value)) {
            locationError.style.display = "none";
        } else {
            locationError.textContent = "Please select a location.";
            locationError.style.display = "block";
        }
    });

    typeField.addEventListener("change", () => {
        const value = typeField.value;
        if (validateType(value)) {
            typeError.style.display = "none";
        } else {
            typeError.textContent = "Please select an announcement type.";
            typeError.style.display = "block";
        }
    });

    imageField.addEventListener("change", async () => { 
        const file = imageField.files[0];
        if (!file) {
            imageError.style.display = "none";  // No error if no image is selected
            return;
        }

        // Validate the selected image
        const isValidImage = await validateImage(file);

        if (isValidImage) {
            imageError.style.display = "none"; // No error if image is valid
        } else {
            imageError.textContent = "Image must be a rectangle ([1,2] or [16,9] or [4,3]), less than 5MB, and in JPG or PNG format.";
            imageError.style.display = "block"; // Show error message
        }
    });

    // Form submission validation
    form.addEventListener("submit", async (event) => {
        let isValid = true;

        // Check if fields are empty
        if (validateFieldEmpty(phoneField.value)) {
            phoneError.textContent = "Phone number is required.";
            phoneError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(emailField.value)) {
            emailError.textContent = "Email address is required.";
            emailError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(priceField.value)) {
            priceError.textContent = "Price is required.";
            priceError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(descriptionField.value)) {
            descriptionError.textContent = "Description is required.";
            descriptionError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(fullNameField.value)) {
            fullNameError.textContent = "Full name is required.";
            fullNameError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(titleField.value)) {
            titleError.textContent = "Title is required.";
            titleError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(dateTimeField.value)) {
            dateTimeError.textContent = "Date and time are required.";
            dateTimeError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(locationField.value)) {
            locationError.textContent = "Location is required.";
            locationError.style.display = "block";
            isValid = false;
        }

        if (validateFieldEmpty(typeField.value)) {
            typeError.textContent = "Type is required.";
            typeError.style.display = "block";
            isValid = false;
        }

        const file = imageField.files[0];

        // Validate image
        if (file) {
            const isValidImage = await validateImage(file);

            if (!isValidImage) {
                imageError.textContent = "Image must be a rectangle, less than 5MB, and in JPG or PNG format.";
                imageError.style.display = "block"; // Show error message
                isValid = false;
            }
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});
