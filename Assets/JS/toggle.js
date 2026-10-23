document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById('container');
    const signUpButton = document.getElementById('register');
    const signInButton = document.getElementById('login');

    // Add event listeners to the buttons to toggle the active class
    signUpButton.addEventListener("click", function () {
        container.classList.add("active");
    });

    signInButton.addEventListener("click", function () {
        container.classList.remove("active");
    });
});
