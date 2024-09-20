<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Update</title>
    <style>
        .success-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-size: 16px;
            display: none; /* Hidden by default */
            position: fixed; /* Fixed position to be visible */
            bottom: 20px; /* Position from bottom */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Center horizontally */
            z-index: 1000; /* Ensure it is on top of other elements */
        }
    </style>
</head>
<body>
    <div id="message-container" class="success-message"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for success message from server (can be set dynamically)
            const message = 'Registered successfully.'; // This would be dynamically set from your backend

            if (message) {
                const messageContainer = document.getElementById('message-container');
                messageContainer.innerHTML = message;
                messageContainer.style.display = 'block';

                // Auto-hide the message after 3 seconds
                setTimeout(() => {
                    messageContainer.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
