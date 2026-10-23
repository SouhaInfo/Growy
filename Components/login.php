<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../Assets/CSS/stylelogin.css">
    <script src="../Assets/JS/toggle.js" defer></script>
    <script src="../Assets/JS/login.js" defer></script>
    <title>Log in and Sign up</title>
</head>
<body>
    <div class="container" id="container">
        <!-- Sign-up Form -->
        <div class="form-container sign-up">
            <form id="signupForm" action="signup_verify.php" method="post">
                <h1>Create Your Account</h1>
                <div class="social-icons">
                    <a href="https://www.facebook.com/profile.php?id=61568305670885" class="icon" target="_blank" aria-label="Facebook Profile">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://x.com/?lang=en" class="icon" target="_blank" aria-label="Twitter Profile">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/growy2024?igsh=em5pMGJnb2k1NHBk&utm_source=qr" class="icon" target="_blank" aria-label="Instagram Profile">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://dz.linkedin.com/in/growy-children-383589335" class="icon" target="_blank" aria-label="LinkedIn Profile">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
                <span>or use your email for registration</span>
                <input type="fullName" id="signupfullName" name="fullName" placeholder="Full name">
                <input type="text" id="signupUsername" name="username" placeholder="Username">
                <input type="email" id="signupEmail" name="email" placeholder="Email">
                <input type="password" id="signupPassword" name="password" placeholder="Password">
                <button type="submit">Sign Up</button>
                <div id="signupError" class="error-message"></div>
            </form>
        </div>

        <!-- Sign-in Form -->
        <div class="form-container sign-in">
            <form id="signinForm" action="login_verify.php" method="post">
                <h2>Welcome Back!</h2>
                <div class="social-icons">
                    <a href="https://www.facebook.com/profile.php?id=61568305670885" class="icon" target="_blank" aria-label="Facebook Profile">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://x.com/?lang=en" class="icon" target="_blank" aria-label="Twitter Profile">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/growy2024?igsh=em5pMGJnb2k1NHBk&utm_source=qr" class="icon" target="_blank" aria-label="Instagram Profile">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://dz.linkedin.com/in/growy-children-383589335" class="icon" target="_blank" aria-label="LinkedIn Profile">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
                <span>or use your email and password</span>
                <hr />
                <input type="email" id="signinEmail" name="email" placeholder="Email">
                <input type="password" id="signinPassword" name="password" placeholder="Password">
                <div class="remember-me">
                    <input type="checkbox" id="rememberMe" name="remember_me">
                    <label for="rememberMe">Remember Me</label>
                </div>
                <button type="submit">Sign In</button>
                <div id="signinError" class="error-message"></div>
            </form>
        </div>

        <!-- Toggle Container -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of the site features.</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Your first time?!</h1>
                    <p>Sign up with your details to access all features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>