class FormValidator {
    constructor(options = {}) {
        this.timeoutDuration = options.timeoutDuration || 5000;
        this.minPasswordLength = options.minPasswordLength || 6;
        this.redirectPath = options.redirectPath || "../Components/homeLI.php";
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.signupForm = document.getElementById('signupForm');
            this.signinForm = document.getElementById('signinForm');
            this.signupError = document.getElementById('signupError');
            this.signinError = document.getElementById('signinError');

            // Initialize form event listeners
            if (this.signupForm) {
                this.signupForm.addEventListener('submit', this.handleSignup.bind(this));
                this.initializeFormValidation(this.signupForm);
            }
            
            if (this.signinForm) {
                this.signinForm.addEventListener('submit', this.handleSignin.bind(this));
                this.initializeFormValidation(this.signinForm);
            }
        });
    }

    initializeFormValidation(form) {
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.validateField(input);
            });

            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    }

    validateField(input) {
        const errorElement = input.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) return;

        let error = '';
        const value = input.value.trim();

        switch (input.type) {
            case 'email':
                if (!value) {
                    error = 'Email is required';
                } else if (!this.validateEmail(value)) {
                    error = 'Please enter a valid email address';
                }
                break;
            case 'password':
                if (!value) {
                    error = 'Password is required';
                } else if (value.length < this.minPasswordLength) {
                    error = `Password must be at least ${this.minPasswordLength} characters`;
                }
                break;
            case 'text':
                if (!value && input.required) {
                    error = `${input.name} is required`;
                }
                break;
        }

        errorElement.textContent = error;
        errorElement.style.display = error ? 'block' : 'none';
        input.classList.toggle('error', !!error);
    }

    validateEmail(email) {
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailPattern.test(email);
    }

    showError(element, message, isHtml = false) {
        if (!element) return;
        
        if (isHtml) {
            element.innerHTML = message;
        } else {
            element.textContent = message;
        }

        element.style.display = "block";
        element.classList.add('error-animate');

        clearTimeout(this.errorTimeout);
        this.errorTimeout = setTimeout(() => {
            element.style.display = "none";
            element.classList.remove('error-animate');
        }, this.timeoutDuration);
    }

    async handleFormSubmission(form, errorElement, endpoint) {
        try {
            const formData = new FormData(form);
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                // Show success message before redirect
                this.showError(errorElement, 
                    '<div class="success-message">✓ Success! Redirecting...</div>', 
                    true
                );
                setTimeout(() => {
                    window.location.href = data.redirect || this.redirectPath;
                }, 1000);
            } else {
                let errorMessage = data.message || "An error occurred";
                if (data.errors && Array.isArray(data.errors)) {
                    errorMessage = data.errors.join('<br>');
                }
                this.showError(errorElement, errorMessage, true);
            }
        } catch (error) {
            console.error('Submission error:', error);
            this.showError(
                errorElement, 
                "Unable to process your request. Please try again later."
            );
        }
    }

    async handleSignup(e) {
        e.preventDefault();
        if (this.validateForm(this.signupForm)) {
            await this.handleFormSubmission(
                this.signupForm, 
                this.signupError, 
                'signup_verify.php'
            );
        }
    }

    async handleSignin(e) {
        e.preventDefault();
        if (this.validateForm(this.signinForm)) {
            await this.handleFormSubmission(
                this.signinForm, 
                this.signinError, 
                'login_verify.php'
            );
        }
    }

    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            this.validateField(input);
            if (input.classList.contains('error')) {
                isValid = false;
            }
        });

        return isValid;
    }
}

// Initialize the form validator with custom options
const formValidator = new FormValidator({
    timeoutDuration: 5000,
    minPasswordLength: 6,
    redirectPath: "../Components/homeLI.php"
});