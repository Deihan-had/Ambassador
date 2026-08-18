<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site_name; ?></title>
  <style>
    :root {
      --primary: <?php echo $primary_color; ?>;
      --primary-hover: #008f5c;
      --bg-dark: #0f172a;
      --bg-card: #1e293b;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --border: #334155;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      background-color: var(--bg-dark);
      color: var(--text-main);
      min-height: 100vh;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .btn-primary {
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      background-color: var(--bg-card);
      padding: 20px;
      border-radius: 12px;
      border: 1px solid var(--border);
    }

    .card h3 {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 8px;
    }

    .card .value {
      font-size: 24px;
      font-weight: bold;
    }

    .table-container {
      background-color: var(--bg-card);
      border-radius: 12px;
      border: 1px solid var(--border);
      padding: 20px;
      overflow-x: auto;
    }

    .table-container h2 {
      font-size: 18px;
      margin-bottom: 16px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 14px;
    }

    th, td {
      padding: 14px 12px;
      border-bottom: 1px solid var(--border);
    }

    th {
      color: var(--text-muted);
      font-weight: 600;
    }

    .status {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status.lunas { background-color: rgba(0, 177, 114, 0.2); color: #00B172; }
    .status.menunggu { background-color: rgba(234, 179, 8, 0.2); color: #eab308; }
  </style>
</head>
<body>