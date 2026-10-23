// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function () {
    // Tab switching functionality
    const tabLinks = document.querySelectorAll('.list-group-item');
    
    // Required fields for each tab
    const forms = {
        'account-general': ['username', 'fullName', 'email'],
        'account-change-password': ['current-password', 'new-password', 'repeat-password'],
        'account-info': ['birthDate', 'country', 'phoneNumber'], // Removed bio from required fields
        'account-notifications': []
    };

    // Validate specific field types
    const validators = {
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        fullName: (value) => value.length >= 2 && /^[a-zA-Z\s]+$/.test(value),
        username: (value) => value.length >= 3,
        password: (value) => value.length >= 6,
        phoneNumber: (value) => /^\+?\d{10,}$/.test(value),
        default: (value) => value.length > 0
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Get the password form
        const passwordForm = document.getElementById('password-form');
        
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                // Don't prevent default form submission - let PHP handle it
                
                const currentPassword = this.querySelector('input[name="current-password"]').value;
                const newPassword = this.querySelector('input[name="new-password"]').value;
                const repeatPassword = this.querySelector('input[name="repeat-password"]').value;
                
                // Basic validation
                if (!currentPassword || !newPassword || !repeatPassword) {
                    e.preventDefault();
                    showNotification('All password fields are required', 'error');
                    return false;
                }
                
                // Check password length
                if (newPassword.length < 6) {
                    e.preventDefault();
                    showNotification('New password must be at least 6 characters long', 'error');
                    return false;
                }
                
                // Check if passwords match
                if (newPassword !== repeatPassword) {
                    e.preventDefault();
                    showNotification('New passwords do not match', 'error');
                    return false;
                }
                
                // If we get here, let the form submit normally to PHP
                return true;
            });
        }
    });
    
    // Notification helper function
    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        
        if (notification && notificationMessage) {
            notification.className = 'notification ' + type;
            notificationMessage.textContent = message;
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
    }
    
    // Function to hide notification
    function hideNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }
    // Handle tab switching with validation
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabLinks.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active', 'show');
            });
            
            // Show the selected tab pane
            const targetId = this.getAttribute('href');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('active', 'show');
            }
        });
    });

    // Form validation and submission for non-password forms
    document.querySelectorAll('.settings-form:not(#password-form)').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                return false;
            }
        });

        // Add real-time validation to required inputs
        form.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('input', function() {
                validateField(this);
            });
        });
    });

    // Real-time password validation
    if (newPassword) {
        newPassword.addEventListener('input', function() {
            if (this.value.length < 8) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    }

    if (repeatPassword) {
        repeatPassword.addEventListener('input', function() {
            if (this.value !== newPassword.value) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            }
        });
    }

    // Utility functions
    function validateField(field) {
        const type = field.type === 'email' ? 'email' : 
                    field.id === 'username' ? 'username' :
                    field.id === 'fullName' ? 'fullName' :
                    field.type === 'password' ? 'password' :
                    field.id === 'phoneNumber' ? 'phoneNumber' : 'default';
                    
        const isValid = validators[type](field.value);
        field.classList.toggle('is-valid', isValid);
        field.classList.toggle('is-invalid', !isValid);
        return isValid;
    }

    function validateForm(form) {
        let isValid = true;
        form.querySelectorAll('input[required]').forEach(input => {
            isValid = validateField(input) && isValid;
        });
        return isValid;
    }

    function resetErrors() {
        if (passwordForm) {
            const inputs = passwordForm.querySelectorAll('input');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
        }
    }

    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        
        if (notification && notificationMessage) {
            notification.className = 'notification ' + type;
            notificationMessage.textContent = message;
            notification.style.display = 'block';
            
            // Hide notification after 5 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }
    }

    // Function to hide notification
    window.hideNotification = function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    };
});