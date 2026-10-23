document.querySelector('#newsletterForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent the default form submission

    const emailInput = document.querySelector('input[name="newsletter_email"]');
    const emailMessage = document.getElementById('emailMessage');

    const formData = new FormData(this); // Collect form data

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
        });

        const result = await response.text(); // Get the response text from PHP

        // Display the response message inline
        emailMessage.innerHTML = result;

        // Optional: Clear the input field if successful
        if (result.includes('green')) {
            emailInput.value = '';
        }
    } catch (error) {
        emailMessage.textContent = 'An error occurred. Please try again.';
        emailMessage.style.color = 'red';
        console.error('Error:', error);
    }
});
