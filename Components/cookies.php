<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if cookie consent cookie exists
$cookieConsent = isset($_COOKIE['cookie-consent']) ? $_COOKIE['cookie-consent'] : '';

// Debug output
echo "<!-- Current cookie value: " . $cookieConsent . " -->\n";

// Set cookie if form submitted
if(isset($_POST['accept_cookies'])) {
    setcookie('cookie-consent', 'accepted', time() + (365 * 24 * 60 * 60), '/');
    $cookieConsent = 'accepted';
    echo "<!-- Cookie was just set via POST -->\n";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Assets/CSS/cookies.css">
</head>
<body>
    <!-- Debug info displayed on page -->
    <div id="debug-info" style="position: fixed; top: 10px; right: 10px; background: #f0f0f0; padding: 10px; display: none;">
        Cookie status: <span id="cookie-status"></span>
    </div>

    <div id="cookies">
        <div class="cookie-container">
            <div class="cookie-subcontainer">
                <div class="cookies">
                    <p>This website uses cookies to ensure you get the best experience on our website.
                        <a href="moreinfo.php">More Info</a>
                    </p>
                    <div class="cook-btn">
                        <button id="cookies-btn">I accept all the cookies</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Show debug info in development
    document.getElementById('debug-info').style.display = 'block';
    
    function updateDebugInfo() {
        const cookieStatus = document.getElementById('cookie-status');
        const cookieConsent = document.cookie
            .split('; ')
            .find(row => row.startsWith('cookie-consent='));
        
        cookieStatus.textContent = cookieConsent ? 
            `Found: ${cookieConsent}` : 
            'Not set';
    }

    // Update debug info immediately and after any changes
    updateDebugInfo();

    if (!document.cookie.includes('cookie-consent=accepted')) {
        document.getElementById('cookies').style.display = 'flex';
        console.log('Cookie consent not found - showing banner');
    } else {
        document.getElementById('cookies').style.display = 'none';
        console.log('Cookie consent found - hiding banner');
    }

    document.getElementById('cookies-btn').addEventListener('click', function() {
        document.cookie = "cookie-consent=accepted; path=/; max-age=" + (60 * 60 * 24 * 365);
        document.getElementById('cookies').style.display = 'none';
        console.log('Cookie consent accepted - cookie set and banner hidden');
        updateDebugInfo();
    });
    </script>
</body>
</html>