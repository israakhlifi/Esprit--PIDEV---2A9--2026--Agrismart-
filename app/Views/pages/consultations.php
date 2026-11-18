<?php
/**
 * app/Views/pages/consultations.php
 * Variables reçues : $consultations (array), $filtre (string)
 */
$badgeClass = ['en_attente'=>'badge-attente','acceptee'=>'badge-acceptee','refusee'=>'badge-refusee','terminee'=>'badge-terminee'];
$badgeLabel = ['en_attente'=>'En attente','acceptee'=>'Acceptée','refusee'=>'Refusée','terminee'=>'Terminée'];
?>

<!-- ── Filtre statut ───────────────────────────────────── -->
<div class="toolbar">
  <label class="form-label" style="margin:0;white-space:nowrap">
    <i class="fa-solid fa-filter"></i> Filtrer :
  </label>
  <select id="filterStatut" class="form-control filter-select">
    <option value="">Tous les statuts</option>
    <option value="en_attente" <?= ($filtre??'')==='en_attente' ?'selected':'' ?>>En attente</option>
    <option value="acceptee"   <?= ($filtre??'')==='acceptee'   ?'selected':'' ?>>Acceptée</option>
    <option value="refusee"    <?= ($filtre??'')==='refusee'    ?'selected':'' ?>>Refusée</option>
    <option value="terminee"   <?= ($filtre??'')==='terminee'   ?'selected':'' ?>>Terminée</option>
  </select>
  <form method="GET" action="index.php" style="display:flex;gap:.5rem">
    <input type="hidden" name="page" value="consultations">
    <select name="filtre" class="form-control filter-select">
      <option value="">Tous</option>
      <option value="en_attente" <?= ($filtre??'')==='en_attente'?'selected':'' ?>>En attente</option>
      <option value="acceptee"   <?= ($filtre??'')==='acceptee'  ?'selected':'' ?>>Acceptée</option>
      <option value="refusee"    <?= ($filtre??'')==='refusee'   ?'selected':'' ?>>Refusée</option>
      <option value="terminee"   <?= ($filtre??'')==='terminee'  ?'selected':'' ?>>Terminée</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-rotate"></i> Appliquer
    </button>
  </form>
</div>

<!-- ── Tableau ─────────────────────────────────────────── -->
<div class="card">
  <div class="card-header">
    <h2><i class="fa-solid fa-comments"></i> Demandes de consultation</h2>
    <span style="font-size:.8rem;color:var(--gris)"><?= count($consultations) ?> résultat(s)</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Agriculteur</th><th>Expert</th>
          <th>Sujet</th><th>Date</th><th>Statut</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($consultations as $c) : ?>
          <tr data-statut="<?= htmlspecialchars($c['statut']??'') ?>">
            <td><?= (int)$c['id'] ?></td>
            <td><?= htmlspecialchars($c['agriculteur']??'-') ?></td>
            <td><?= htmlspecialchars($c['expert']??'-') ?></td>
            <td><?= htmlspecialchars($c['sujet']??'-') ?></td>
            <td><?= htmlspecialchars($c['date_souhaitee']??'-') ?></td>
            <td>
              <span class="badge <?= $badgeClass[$c['statut']??'']??'badge-attente' ?>">
                <?= htmlspecialchars($badgeLabel[$c['statut']??'']??'-') ?>
              </span>
            </td>
            <td style="white-space:nowrap">
              <button class="btn btn-secondary btn-sm"
                onclick="openConsultationDetail(<?= htmlspecialchars(json_encode([
                  'agriculteur' => $c['agriculteur'],
                  'expert'      => $c['expert'],
                  'sujet'       => $c['sujet'],
                  'date'        => $c['date_souhaitee'],
                  'description' => $c['description'],
                  'statut'      => $c['statut'],
                ])) ?>)">
                <i class="fa-solid fa-eye"></i>
              </button>
              <?php if (($c['statut']??'')==='en_attente') : ?>
                <button class="btn btn-primary btn-sm"
                  onclick="alert('Consultation acceptée (simulation)')">
                  <i class="fa-solid fa-check"></i> Accepter
                </button>
                <button class="btn btn-ocre btn-sm"
                  onclick="alert('Consultation refusée (simulation)')">
                  <i class="fa-solid fa-xmark"></i> Refuser
                </button>
              <?php endif; ?>
              <button class="btn btn-danger btn-sm"
                onclick="confirmDelete('#','<?= htmlspecialchars(addslashes($c['sujet']??'')) ?>')">
                <i class="fa-solid fa-trash"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ══ Modal détail consultation ═════════════════════════ -->
<div class="modal-overlay" id="modalConsultDetail">
  <div class="modal-box modal-lg">
    <div class="modal-header">
      <h3><i class="fa-solid fa-file-lines"></i> Détail de la consultation</h3>
      <button class="modal-close" data-modal-close="modalConsultDetail">&times;</button>
    </div>
    <div class="modal-body">
      <div class="detail-grid">
        <div class="detail-item"><div class="dt">Agriculteur</div><div class="dd" id="dAgriculteur">—</div></div>
        <div class="detail-item"><div class="dt">Expert</div><div class="dd" id="dExpert">—</div></div>
        <div class="detail-item"><div class="dt">Sujet</div><div class="dd" id="dSujet">—</div></div>
        <div class="detail-item"><div class="dt">Date souhaitée</div><div class="dd" id="dDate">—</div></div>
        <div class="detail-item"><div class="dt">Statut</div><div class="dd"><span class="badge" id="dStatut">—</span></div></div>
        <div class="detail-item full"><div class="dt">Description</div><div class="dd" id="dDescription" style="white-space:pre-line">—</div></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-modal-close="modalConsultDetail">Fermer</button>
    </div>
  </div>
</div>

<!-- ══ Modal confirmation suppression ════════════════════ -->
<div class="modal-overlay" id="modalConfirmDelete">
  <div class="modal-box modal-sm">
    <div class="modal-header">
      <h3><i class="fa-solid fa-trash"></i> Confirmer la suppression</h3>
      <button class="modal-close" data-modal-close="modalConfirmDelete">&times;</button>
    </div>
    <div class="modal-body"><p id="confirmDeleteMsg"></p></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-modal-close="modalConfirmDelete">Annuler</button>
      <button class="btn btn-danger"
              onclick="closeModal('modalConfirmDelete');alert('Suppression simulée.')">
        <i class="fa-solid fa-trash"></i> Supprimer
      </button>
    </div>
  </div>
</div>
