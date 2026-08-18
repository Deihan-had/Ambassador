<style>
  .sidebar {
    width: 260px;
    background-color: var(--bg-card);
    border-right: 1px solid var(--border);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 30px;
  }

  .brand {
    font-size: 20px;
    font-weight: bold;
    color: var(--primary);
  }

  .menu {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .menu li a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: var(--text-muted);
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s;
  }

  .menu li.active a, .menu li a:hover {
    background-color: var(--primary);
    color: #fff;
  }
</style>

<div class="sidebar">
  <div class="brand">
    🛍️ Ambassador Admin
  </div>
  <ul class="menu">
    <li class="active"><a href="index.php">📊 Dashboard</a></li>
    <li><a href="#">📦 Kelola Produk</a></li>
    <li><a href="#">🏷️ Promo & Flash Sale</a></li>
    <li><a href="#">🛒 Pesanan Masuk</a></li>
    <li><a href="#">👥 Pelanggan</a></li>
    <li><a href="#">⚙️ Pengaturan Toko</a></li>
  </ul>
</div>