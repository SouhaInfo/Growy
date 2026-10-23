    <?php
    session_start();
    include "db_connect.php";
    $userID = $_SESSION["userID"];
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // Add cache control headers
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    $userDataStmt = $conn->prepare("SELECT * FROM user WHERE userID = ?");
if ($userDataStmt) {
    $userDataStmt->bind_param("i", $userID);
    $userDataStmt->execute();
    $userResult = $userDataStmt->get_result();
    $userData = $userResult->fetch_assoc();
    $userResult->close();
    $userDataStmt->close();
}

    // Check if user is logged in
    if (!isset($_SESSION["userID"])) {
        header("Location: login.php");
        exit();
    }

    // Check database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to delete old profile photo
    function deleteOldProfilePhoto($conn, $userID)
    {
        $stmt = $conn->prepare("SELECT profilePhoto FROM user WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $oldPhoto = $row["profilePhoto"];

        // Only delete if it's not the default photo
        if (
            $oldPhoto &&
            $oldPhoto !== "../Assets/images/img/cropped_image.png" &&
            file_exists($oldPhoto)
        ) {
            unlink($oldPhoto);
        }
        $stmt->close();
    }

    $profilePhoto = $_SESSION["profilePhoto"] ?? null;

    if (!$profilePhoto) {
        $sql = "SELECT profilePhoto FROM user WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $profilePhoto = $row["profilePhoto"] ?? null;
            $result->close();
            $stmt->close();
        }
    }

    // If no profile photo is found, set it to the default image
    if (empty($profilePhoto)) {
        $defaultProfilePic = "../Assets/images/img/cropped_image.png";
        $profilePhoto = $defaultProfilePic;
        $_SESSION["profilePhoto"] = $defaultProfilePic;
    }

    $email = $_SESSION["email"];
    $username = $_SESSION["username"] ?? "";

    // Handle form submissions
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["reset_photo"])) {
            $defaultProfilePic = "../Assets/images/img/cropped_image.png";
            deleteOldProfilePhoto($conn, $userID);
            $updateStmt = $conn->prepare(
                "UPDATE user SET profilePhoto = ? WHERE userID = ?",
            );
            if ($updateStmt) {
                $updateStmt->bind_param("si", $defaultProfilePic, $userID);
                if ($updateStmt->execute()) {
                    $_SESSION["profilePhoto"] = $defaultProfilePic;
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Profile photo reset successfully!', 'success');
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Error resetting profile photo: " .
                        $conn->error .
                        "', 'error');
                        });
                    </script>";
                }
                $updateStmt->close();
            }
        }
            }        // Handle general settings update
        if (isset($_POST["update_general"])) {
            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $fullname = trim($_POST["fullname"]);
            $company = trim($_POST["company"] ?? "");
            $imagePath = $profilePhoto; // Use existing profile photo by default

            if (
                isset($_FILES["profilePhoto"]) &&
                $_FILES["profilePhoto"]["error"] === UPLOAD_ERR_OK
            ) {
                $imageTmpPath = $_FILES["profilePhoto"]["tmp_name"];
                $imageName = $_FILES["profilePhoto"]["name"];
                $imageSize = $_FILES["profilePhoto"]["size"];
                $imageType = $_FILES["profilePhoto"]["type"];

                $errors = []; // Array to store errors

                // File validation: Only allow images
                $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                if (!in_array($imageType, $allowedTypes)) {
                    $errors["image"] = "Only JPG, PNG, and GIF images are allowed.";
                }

                // File size validation (limit 5MB)
                if ($imageSize > 5000000) {
                    $errors["image"] = "File size exceeds the allowed 5MB limit.";
                }

                if (empty($errors)) {
                    // Delete old photo before uploading new one
                    deleteOldProfilePhoto($conn, $userID);

                    // Generate a unique filename to prevent overwriting
                    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                    $safeFileName = preg_replace(
                        "/[^a-zA-Z0-9-_\.]/",
                        "_",
                        pathinfo($imageName, PATHINFO_FILENAME),
                    );
                    $newImageName =
                        $safeFileName .
                        "_" .
                        uniqid() .
                        "." .
                        strtolower($imageExtension);
                    $uploadDir = "../Assets/images/img/";
                    $imagePath = $uploadDir . $newImageName;

                    // Ensure the directory exists
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Move uploaded file to the server directory
                    if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                        $errors["image"] = "Failed to upload the image.";
                        $imagePath = $profilePhoto; // Keep existing profile photo if upload fails
                    }
                }

                // Handle any errors
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo "<script>alert('" .
                            htmlspecialchars($error) .
                            "');</script>";
                    }
                }
            }

            $updateStmt = $conn->prepare(
                "UPDATE user SET username = ?, email = ?, fullname = ?, company = ?, profilePhoto = ? WHERE userID = ?",
            );
            if ($updateStmt) {
                $updateStmt->bind_param(
                    "sssssi",
                    $username,
                    $email,
                    $fullname,
                    $company,
                    $imagePath,
                    $userID,
                );
                if ($updateStmt->execute()) {
                    $_SESSION["profilePhoto"] = $imagePath;
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Profile updated successfully!', 'success');
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showNotification('Error updating profile: " .
                        $conn->error .
                        "', 'error');
                        });
                    </script>";
                }
                $updateStmt->close();
            }
        }

        // Handle password update
        if (isset($_POST["update_password"])) {
            $currentPassword = $_POST["current-password"];
            $newPassword = $_POST["new-password"];
            $repeatPassword = $_POST["repeat-password"];

            $passwordStmt = $conn->prepare(
                "SELECT password FROM user WHERE userID = ?",
            );
            if ($passwordStmt) {
                $passwordStmt->bind_param("i", $userID);
                $passwordStmt->execute();
                $passwordResult = $passwordStmt->get_result();
                $user = $passwordResult->fetch_assoc();
                $passwordResult->close();
                $passwordStmt->close();

                if (password_verify($currentPassword, $user["password"])) {
                    if ($newPassword === $repeatPassword) {
                        if (strlen($newPassword) >= 6) {
                            $hashedPassword = password_hash(
                                $newPassword,
                                PASSWORD_DEFAULT,
                            );
                            $updatePasswordStmt = $conn->prepare(
                                "UPDATE user SET password = ? WHERE userID = ?",
                            );
                            if ($updatePasswordStmt) {
                                $updatePasswordStmt->bind_param(
                                    "si",
                                    $hashedPassword,
                                    $userID,
                                );
                            
                                if ($updatePasswordStmt->execute()) {
                                    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Password updated successfully!');
            });
        </script>";
                                } else {
                                    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Error happend while updating the password , please check the data base!');
            });
        </script>";
                                }
                                $updatePasswordStmt->close();
                            }
                        } else {
                            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('Password must be at least 6 charecters long!');
            });
        </script>";
                        }
                    } else {
                        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('New paswords dont match!');
            });
        </script>";
                    }
                } else {
                    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('current password is incorrect!');
            });
        </script>";
                }
            }
        }

        // Handle info update
if (isset($_POST["update_info"])) {
    // Get values directly from POST data
    $bio = trim($_POST["bio"] ?? "");
    $birthDate = $_POST["birthDate"] ?? null;
    $phoneNumber = trim($_POST["phoneNumber"] ?? ""); // This will be empty string if not set
    
    // Debug line
    error_log("Submitted phone number: " . $phoneNumber);
    
    // Prepare the update query
    $infoStmt = $conn->prepare(
        "UPDATE user SET bio = ?, birthDate = ?, phoneNumber = ? WHERE userID = ?"
    );
    
    if ($infoStmt) {
        $infoStmt->bind_param(
            "sssi",
            $bio,
            $birthDate,
            $phoneNumber,
            $userID
        );
        
        if ($infoStmt->execute()) {
            // Update the local userData array to reflect the changes
            $userData['bio'] = $bio;
            $userData['birthDate'] = $birthDate;
            $userData['phoneNumber'] = $phoneNumber;
            
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Profile info updated successfully!', 'success');
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showNotification('Error updating profile info: " . $conn->error . "', 'error');
                });
            </script>";
        }
        $infoStmt->close();
    }
}

    // Fetch current user data
    $userDataStmt = $conn->prepare("SELECT * FROM user WHERE userID = ?");
if ($userDataStmt) {
    $userDataStmt->bind_param("i", $userID);
    $userDataStmt->execute();
    $userResult = $userDataStmt->get_result();
    $userData = $userResult->fetch_assoc();
    $userResult->close();
    $userDataStmt->close();
}
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Settings</title>
        <link rel="stylesheet" href="../Assets/CSS/settingsstyle.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <header>
        <?php 
        if ($userID == Null) {
            // If userID is null, include 'header.php'
            if ($userID == null) {
                header("Location: login.php");
                exit; // Always include exit after a header redirect
            }
            
        } elseif($userID<=60 && $userID>=57) {
            include 'adminHeader.php'; 
        }else{
            include 'headerlogin.php'; 
        }
        ?>
</header>
        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4" style="color: black;">
                Account settings
            </h4>
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list"
                                href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-change-password">Change password</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-info">Info</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-connections">Your Data</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-notifications">Notifications</a>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="account-general">
                                <form method="POST" class="settings-form" enctype="multipart/form-data">
                                    <div class="card-body media align-items-center">
                                        <img src="<?php echo htmlspecialchars($profilePhoto . '?v=' . time()); ?>" alt="Profile" class="d-block ui-w-80">
                                        <div class="media-body ml-4">
                                            <label class="btn btn-outline-primary">
                                                Upload new photo
                                                <input type="file" class="account-settings-fileinput" name="profilePhoto">
                                            </label> &nbsp;
                                            <button type="button" class="btn btn-default md-btn-flat">Reset</button>
                                        </div>
                                    </div>
                                    <hr class="border-light m-0">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control mb-1" 
                                                value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="fullname" class="form-control" 
                                                value="<?php echo htmlspecialchars($userData['fullname'] ?? ''); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">E-mail</label>
                                            <input type="email" name="email" class="form-control mb-1" 
                                                value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Company or School (optional)</label>
                                            <input type="text" name="company" class="form-control" 
                                                value="<?php echo htmlspecialchars($userData['company'] ?? ''); ?>">
                                        </div>
                                        <button type="submit" name="update_general" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="account-change-password">
                                <form method="POST" class="settings-form" id="password-form">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Current password</label>
                                            <input type="password" name="current-password" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">New password</label>
                                            <input type="password" name="new-password" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Repeat new password</label>
                                            <input type="password" name="repeat-password" class="form-control" required>
                                        </div>
                                        <button type="submit" name="update_password" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="account-info">
                                <form method="POST" class="settings-form">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Bio</label>
                                            <textarea name="bio" class="form-control" rows="5"><?php echo htmlspecialchars($userData['bio'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Birthday</label>
                                            <input type="date" name="birthDate" class="form-control" 
                                                value="<?php echo htmlspecialchars($userData['birthDate'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <select name="country" class="custom-select">
                                                <option value="">Select Country</option>
                                                <option value="Algeria" <?php echo ($userData['country'] ?? '') === 'Algeria' ? 'selected' : ''; ?>>Algeria</option>
                                                <option value="Jordan" <?php echo ($userData['country'] ?? '') === 'Syria' ? 'selected' : ''; ?>>Syria</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Phone</label>
                                            <input type="tel" name="phoneNumber" class="form-control" 
                                            value="<?php echo htmlspecialchars($userData['phoneNumber'] ?? ''); ?>">
                                        </div>
                                        <button type="submit" name="update_info" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="account-connections">
                                <div class="card-body">
                                    <button type="button" class="btn btn-twitter" onclick="window.location.href='../Components/coursesLI.php';">Go to my <strong>Courses</strong></button>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <button type="button" class="btn btn-instagram" onclick="window.location.href='../Components/My_Contributions_LI.php';">Go to my <strong>Contributions</strong></button>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-instagram" onclick="window.location.href='../Components/myannouncementsLI.php';">Go to my <strong>Announcements</strong></button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account-notifications">
                                <form method="POST" class="settings-form">
                                    <div class="card-body pb-2">
                                        <h6 class="mb-4">Message Notifications:</h6>
                                        <div class="form-group">
                                            <label class="switcher">
                                                <input type="checkbox" name="message_notif" class="switcher-input" 
                                                    <?php echo ($userData['message_notifications'] ?? 1) ? 'checked' : ''; ?>>
                                                <span class="switcher-indicator">
                                                    <span class="switcher-yes">
                                                    <span class="switcher-no"></span>
                                                </span>
                                                <span class="switcher-label">Notify me when there is a new announcement in my school group.</span>
                                            </label>
                                        </div>
                                        <button type="submit" name="update_notifications" class="btn btn-primary">Save notification settings</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="notification" class="notification">
        <span id="notification-message"></span>
       

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Add client-side form validation
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.settings-form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    });
                });
            });
        </script>
        <div id="notification" style="display: none;">
        <span id="notification-message"></span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('.account-settings-fileinput');
            const profileImage = document.querySelector('.ui-w-80');
            const defaultImagePath = '../Assets/images/img/cropped_image.png';
            const form = document.querySelector('.settings-form');
            
            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Reset button functionality
            const resetButton = document.querySelector('.btn-default');
            
            resetButton.addEventListener('click', function() {
                profileImage.src = defaultImagePath;
                fileInput.value = '';
                
                // Create hidden input for reset action
                let resetInput = document.createElement('input');
                resetInput.type = 'hidden';
                resetInput.name = 'reset_photo';
                resetInput.value = 'true';
                form.appendChild(resetInput);
                
                // Submit the form
                form.submit();
            });
        });
    </script>
    </body>
    
    </html>