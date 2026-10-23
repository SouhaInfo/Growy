// Check if the cookie consent has been accepted
if (!document.cookie.includes('cookie-consent=accepted')) {
    document.getElementById('cookies').style.display = 'flex'; // Show the cookie consent message
} else {
    document.getElementById('cookies').style.display = 'none'; // Hide it if consent is already given
}

// When the "I accept all the cookies" button is clicked
document.getElementById('cookies-btn').addEventListener('click', function() {
    // Set a cookie to remember the user's consent
    document.cookie = "cookie-consent=accepted; path=/; max-age=" + (60 * 60 * 24 * 365); // 1 year
    document.getElementById('cookies').style.display = 'none'; // Hide the consent message
});
