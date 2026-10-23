// Flag to check if the form has been submitted or data is saved
let isFormSubmitted = false;

// Listen for form submission
document.querySelector('form').addEventListener('submit', function() {
    isFormSubmitted = true; // Set flag to true when form is submitted
});

// Listen for the "beforeunload" event to warn the user about unsaved data
window.addEventListener('beforeunload', function(event) {
    if (!isFormSubmitted) { // If form is not submitted
        // Custom warning message for the user
        const message = "The page that you're looking for used information that you entered. Returning to that page might cause any action that you took to be repeated. Do you want to continue?";
        
        // Standard way to trigger the warning in modern browsers
        event.returnValue = message; 
        return message;  // Some older browsers require returning the message
    }
});

// If you want to clear the warning after successful submission (e.g., PHP action):
// You can disable the event listener or change the flag in a success callback or upon page redirection
