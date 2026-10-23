<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User List</title>
    <link rel="stylesheet" href="/Assets/CSS/adminUsers.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap">
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
            header("Location: login.php");
                exit; // Always include exit after a header redirect
        }
        ?>
</header>

    <main>
        <h2>Growy users list</h2>
        <div class="container">
            <table class="user-list">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Birthdate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <!-- Additional rows can be added dynamically here -->
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>mark_williams</td>
                        <td>Mark Williams</td>
                        <td>(555) 123-4567</td>
                        <td>mark.williams@example.com</td>
                        <td>1992-02-10</td>
                        <td>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </main>

    <div class="copyright">
        <p>Growy &copy; 2024 - All rights reserved</p>
    </div>

</body>

</html>
