<!DOCTYPE html>
<html>
<head>
    <title>Authentication</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
    .notification {
        background-color: #ffe066;
        border: 7px solid #ffd700;
        padding: 0;
        border-radius: 12px;
        text-align: center;
        max-width: 470px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 10%;
        font-family: 'Poppins';
        font-size: 20px;
    }
    .button {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 5px 12px;
        margin: 3px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 15px;
        font-family: 'Poppins';
        transition-duration: 0.5s;
        cursor: pointer;
    }
    .button2 {
        background-color: #fff; 
        color: #0d6efd; 
        border: 2px solid #0d6efd;
        border-radius: 7px;
    }

    .button2:hover {
        background-color: #0d6efd;
        color: #fff;
        border-radius: 13px;
        font-size: 20px;
    }
    </style>
</head>
<body>
    <div class="notification">
        <h6><i class="bi bi-exclamation-triangle-fill" style="color:red"></i> Anda belum login. Silakan <a  href="login.php"><button type="submits" class="button button2">Login</button></a> terlebih dahulu.</h6>
    </div>
</body>
</html>