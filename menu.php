<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 20px; }
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .map { height: 400px; border-radius: 12px; margin-bottom: 20px; }
        .countdown { font-weight: bold; color: #007bff; font-size: 18px; }
        .chart-container { position: relative; height: 200px; }
        nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="box.php?box=5e36c98c6b0685001b63357d&country=Germany">Germany</a>
    <a href="box.php?box=5c796d245094e90019293adf&country=United Kingdom">United Kingdom</a>
</nav>
