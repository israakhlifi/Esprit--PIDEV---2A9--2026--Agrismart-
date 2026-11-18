<?php
/**
 * app/Views/layout.php
 * Assemble tous les partials + injecte $content au milieu.
 *
 * Variables attendues (fournies par Controller::render()) :
 *   $title        string  — titre de l'onglet <title>
 *   $currentPage  string  — clé active pour la sidebar
 *   $content      string  — HTML du contenu (déjà rendu)
 *   $extraScripts string  — scripts JS spécifiques à la page (optionnel)
 */
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/sidebar.php';
?>

<div class="admin-wrapper">
  <!-- sidebar est incluse avant, hors du wrapper pour le positionnement fixed -->

  <div class="main-content">

    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <main class="page-content">
      <?= $content ?>
    </main>

    <?php require_once __DIR__ . '/partials/footer.php'; ?>

  </div><!-- /main-content -->
</div><!-- /admin-wrapper -->

<?php require_once __DIR__ . '/partials/scripts.php'; ?>
