<style>
    .admin-footer {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      color: var(--text-muted);
    }

    .admin-footer a {
      color: var(--primary);
      text-decoration: none;
    }

    .footer-status {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .status-dot {
      width: 8px;
      height: 8px;
      background-color: var(--primary);
      border-radius: 50%;
      display: inline-block;
    }
  </style>

  <footer class="admin-footer">
    <div>
      &copy; <?php echo date('Y'); ?> <strong><?php echo $site_name; ?></strong>. Hak cipta dilindungi undang-undang.
    </div>
    <div class="footer-status">
      <span class="status-dot"></span>
      <span>System Status: <strong>Online</strong></span> | 
      <span id="current-time"></span>
    </div>
  </footer>

</div> <!-- Penutup .main-content -->

<script>
  function updateTime() {
    const now = new Date();
    const options = { 
      day: 'numeric', 
      month: 'short', 
      year: 'numeric', 
      hour: '2-digit', 
      minute: '2-digit', 
      second: '2-digit' 
    };
    document.getElementById('current-time').innerText = now.toLocaleDateString('id-ID', options);
  }
  setInterval(updateTime, 1000);
  updateTime();
</script>

</body>
</html>