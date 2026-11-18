<!-- header.php — identique sur toutes les pages, aucune logique PHP -->
<header class="navbar-admin">
  <div class="navbar-left">
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="fa-solid fa-bars"></i>
    </button>
    <span class="page-title"><?= htmlspecialchars($title ?? '') ?></span>
  </div>
  <div class="navbar-right">
    <div class="user-info">
      <div class="name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></div>
      <div class="role"><?= htmlspecialchars(ucfirst($_SESSION['role'] ?? 'admin')) ?></div>
    </div>
    <form method="POST" action="index.php?page=logout" style="margin:0">
      <button type="submit" class="btn-logout">
        <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
      </button>
    </form>
  </div>
</header>
