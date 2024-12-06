<html lang="en">
<head>
    <title>Vehicle Registration System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #FAF6E3; /* Light Cream */
            font-family: Arial, sans-serif;
        }

        h1 {
            font-size: 50px; /* Set heading font size to 50px */
            font-weight: bold;
            color: #4B5945; /* Deep Olive */
            margin-bottom: 60px;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
        }

        button {
            background-color: #66785F; /* Muted Green */
            color: #FFF;
            padding: 28px 72px; /* Larger padding */
            border-radius: 50px;
            font-size: 1.75rem; /* Enlarged button font size */
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #B2C9AD; /* Soft Light Green */
            transform: scale(1.1); /* More zoom on hover */
        }

        /* Centering the login form */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            min-height: 100vh; /* Full screen height */
        }

        .login-container {
            padding: 80px;
            background-color: white;
            border-radius: 25px; /* Even bigger border radius */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px; /* Wider container */
            width: 100%;
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            h1 {
                font-size: 3rem; /* Slightly smaller but still large */
                margin-bottom: 50px;
            }

            button {
                padding: 22px 60px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="wrapper w-full">
        <!-- Login Form Container (Centered and Enlarged) -->
        <div class="login-container">
            <h1>Welcome to the Vehicle Registration System</h1>

            <a href="login.php">
                <button class="hover:bg-green-600 transition duration-300">Login</button>
            </a>
        </div>
    </div>

</body>
</html>
