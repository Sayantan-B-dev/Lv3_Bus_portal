<?php
/**
 * views/errors/500.php
 */
// Fallback layout since regular header might fail
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 Server Error — Bus Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&family=Noto+Sans&display=swap" rel="stylesheet">
    <style>
        body { background: #0d0d0d; color: #6b6b6b; font-family: 'Noto Sans', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; text-align: center; }
        .box { padding: 40px; border: 1px solid #2a2a2a; }
        h1 { font-family: 'Rajdhani', sans-serif; color: #e8b84b; margin: 0; font-size: 80px; }
        p { margin-bottom: 30px; }
        a { color: #e8b84b; text-decoration: none; border: 1px solid #e8b84b; padding: 10px 20px; font-family: 'Rajdhani', sans-serif; }
    </style>
</head>
<body>
    <div class="box">
        <h1>500</h1>
        <h2 style="font-family: 'Rajdhani'; color: #fff;">ENGINE BREAKDOWN.</h2>
        <p>Something went wrong on our end. We're working to get the bus back on track.</p>
        <a href="/">HOME PORTAL</a>
    </div>
</body>
</html>
