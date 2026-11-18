<?php
/**
 * app/Views/pages/dashboard.php
 * Contenu pur — head, sidebar, header, footer et scripts sont injectés par layout.php
 * Variables reçues : $stats (array), $lastConsultations (array)
 */
$badgeClass = ['en_attente'=>'badge-attente','acceptee'=>'badge-acceptee','refusee'=>'badge-refusee','terminee'=>'badge-terminee'];
$badgeLabel = ['en_attente'=>'En attente','acceptee'=>'Acceptée','refusee'=>'Refusée','terminee'=>'Terminée'];
?>

<!-- ── Cartes statistiques ──────────────────────────────── -->
<div class="stats-grid">

  <div class="stat-card">
    <div class="stat-icon green"><i class="fa-solid fa-user-tie"></i></div>
    <div class="stat-info">
      <div class="value"><?= (int)($stats['experts'] ?? 0) ?></div>
      <div class="label">Experts enregistrés</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon ocre"><i class="fa-solid fa-clock"></i></div>
    <div class="stat-info">
      <div class="value"><?= (int)($stats['consultations_en_attente'] ?? 0) ?></div>
      <div class="label">Consultations en attente</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon blue"><i class="fa-solid fa-calendar-check"></i></div>
    <div class="stat-info">
      <div class="value"><?= (int)($stats['rdv_a_venir'] ?? 0) ?></div>
      <div class="label">Rendez-vous à venir</div>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon purple"><i class="fa-solid fa-lightbulb"></i></div>
    <div class="stat-info">
      <div class="value"><?= (int)($stats['conseils_donnes'] ?? 0) ?></div>
      <div class="label">Conseils donnés</div>
    </div>
  </div>

</div>

<!-- ── Tableau 5 dernières consultations ───────────────── -->
<div class="card">
  <div class="card-header">
    <h2><i class="fa-solid fa-list-ul"></i> Dernières consultations</h2>
    <a href="index.php?page=consultations" class="btn btn-outline btn-sm">
      Voir tout <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Agriculteur</th>
          <th>Expert</th>
          <th>Sujet</th>
          <th>Date souhaitée</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($lastConsultations)) : ?>
          <tr>
            <td colspan="5" style="text-align:center;color:var(--gris);padding:2rem">
              Aucune consultation enregistrée.
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($lastConsultations as $c) : ?>
            <tr>
              <td><?= htmlspecialchars($c['agriculteur'] ?? '-') ?></td>
              <td><?= htmlspecialchars($c['expert']      ?? '-') ?></td>
              <td><?= htmlspecialchars($c['sujet']       ?? '-') ?></td>
              <td><?= htmlspecialchars($c['date_souhaitee'] ?? '-') ?></td>
              <td>
                <span class="badge <?= $badgeClass[$c['statut'] ?? ''] ?? 'badge-attente' ?>">
                  <?= htmlspecialchars($badgeLabel[$c['statut'] ?? ''] ?? '-') ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
