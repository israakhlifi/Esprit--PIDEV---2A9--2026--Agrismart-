<?php
// $currentPage est fourni par le Controller
$currentPage = $currentPage ?? 'dashboard';

function navActive(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}
?>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <img src="app/Views/assets/img/logo.svg" alt="Logo AgriSmart">
    <span>AgriSmart</span>
  </div>

  <nav>
    <p class="sidebar-label">Principal</p>
    <ul>
      <li>
        <a href="index.php?page=dashboard" class="<?= navActive('dashboard', $currentPage) ?>">
          <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
      </li>
      <li>
        <a href="index.php?page=experts" class="<?= navActive('experts', $currentPage) ?>">
          <i class="fa-solid fa-user-tie"></i> Experts
        </a>
      </li>
      <li>
        <a href="index.php?page=consultations" class="<?= navActive('consultations', $currentPage) ?>">
          <i class="fa-solid fa-comments"></i> Consultations
        </a>
      </li>
      <li>
        <a href="index.php?page=rendez_vous" class="<?= navActive('rendez_vous', $currentPage) ?>">
          <i class="fa-solid fa-calendar-check"></i> Rendez-vous
        </a>
      </li>
      <li>
        <a href="index.php?page=conseils" class="<?= navActive('conseils', $currentPage) ?>">
          <i class="fa-solid fa-lightbulb"></i> Conseils
        </a>
      </li>
    </ul>

    <p class="sidebar-label">Autres modules</p>
    <ul>
      <li><a href="#"><i class="fa-solid fa-users"></i> Utilisateurs</a></li>
      <li><a href="#"><i class="fa-solid fa-seedling"></i> Parcelles</a></li>
      <li><a href="#"><i class="fa-solid fa-book-open"></i> Ressources</a></li>
      <li><a href="#"><i class="fa-solid fa-chart-pie"></i> Statistiques</a></li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <a href="#"><i class="fa-solid fa-arrow-left"></i> Retour au site</a>
  </div>
</aside>
