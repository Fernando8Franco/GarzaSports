<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        /* Reset some default styles for consistency */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        /* Center the content vertically and horizontally */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        
        /* Style the error message */
        .error-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .error-code {
            font-size: 100px;
            color: #333;
        }
        
        .error-message {
            font-size: 24px;
            color: #555;
        }
        
        /* Style the back to home button */
        .back-button {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Pagina no encontrada</div>
    </div>
    <a href="/public" class="back-button">Regresar</a>
</body>
</html>
