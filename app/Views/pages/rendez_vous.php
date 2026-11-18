<?php
/**
 * app/Views/pages/rendez_vous.php
 * Variables reçues : $rdvs, $consultationsAcceptees, $errors, $old
 */
$badgeRdv = ['planifie'=>'badge-planifie','termine'=>'badge-termine','annule'=>'badge-annule'];
$labelRdv = ['planifie'=>'Planifié','termine'=>'Terminé','annule'=>'Annulé'];
?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start">

  <!-- ── Tableau ──────────────────────────────────────── -->
  <div class="card">
    <div class="card-header">
      <h2><i class="fa-solid fa-calendar-check"></i> Rendez-vous planifiés</h2>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Consultation</th><th>Agriculteur</th>
            <th>Expert</th><th>Date &amp; Heure</th><th>Mode</th><th>Statut</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rdvs as $r) : ?>
            <tr>
              <td><?= (int)$r['id'] ?></td>
              <td><?= htmlspecialchars($r['consultation']??'-') ?></td>
              <td><?= htmlspecialchars($r['agriculteur']??'-') ?></td>
              <td><?= htmlspecialchars($r['expert']??'-') ?></td>
              <td><?= htmlspecialchars($r['date_heure']??'-') ?></td>
              <td>
                <?php if (($r['mode']??'')==='en_ligne') : ?>
                  <i class="fa-solid fa-video" style="color:var(--vert-moyen)"></i> En ligne
                <?php else : ?>
                  <i class="fa-solid fa-map-marker-alt" style="color:var(--ocre)"></i> Sur place
                <?php endif; ?>
              </td>
              <td>
                <span class="badge <?= $badgeRdv[$r['statut']??'']??'badge-planifie' ?>">
                  <?= htmlspecialchars($labelRdv[$r['statut']??'']??'-') ?>
                </span>
              </td>
              <td style="white-space:nowrap">
                <?php if (($r['statut']??'')==='planifie') : ?>
                  <button class="btn btn-outline btn-sm"
                    onclick="openEditRdv(<?= htmlspecialchars(json_encode([
                      'id'            => $r['id'],
                      'consultationId'=> $r['consultationId'] ?? '',
                      'date_heure'    => $r['date_heure'],
                      'mode'          => $r['mode'],
                    ])) ?>)">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="btn btn-danger btn-sm"
                    onclick="if(confirm('Annuler ce rendez-vous ?')) alert('Annulé (simulation)')">
                    <i class="fa-solid fa-ban"></i>
                  </button>
                <?php else : ?>
                  <span style="color:var(--gris);font-size:.78rem">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Formulaire planification ─────────────────────── -->
  <div class="card">
    <div class="card-header">
      <h2 id="formRdvTitle"><i class="fa-solid fa-calendar-plus"></i> Planifier un rendez-vous</h2>
    </div>
    <form id="formRdv" onsubmit="submitRdvForm(event)" class="modal-body">
      <input type="hidden" name="rdv_id" id="rdv_id" value="">

      <div class="form-group">
        <label class="form-label">Consultation (acceptée) <span style="color:red">*</span></label>
        <select name="consultation_id" id="consultation_id" class="form-control">
          <option value="">— Sélectionner —</option>
          <?php foreach ($consultationsAcceptees as $co) : ?>
            <option value="<?= (int)$co['id'] ?>"
              <?= (($old['consultation_id']??'')==$co['id'])?'selected':'' ?>>
              <?= htmlspecialchars($co['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Sélectionner une consultation.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Date &amp; Heure <span style="color:red">*</span></label>
        <input type="datetime-local" name="date_heure" id="date_heure" class="form-control"
               value="<?= htmlspecialchars($old['date_heure']??'') ?>">
        <div class="invalid-feedback">Date future obligatoire.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Mode <span style="color:red">*</span></label>
        <select name="mode" id="mode_rdv" class="form-control">
          <option value="">— Sélectionner —</option>
          <option value="en_ligne"  <?= (($old['mode']??'')==='en_ligne') ?'selected':'' ?>>En ligne</option>
          <option value="sur_place" <?= (($old['mode']??'')==='sur_place')?'selected':'' ?>>Sur place</option>
        </select>
        <div class="invalid-feedback">Sélectionner un mode.</div>
      </div>

      <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:.5rem">
        <button type="button" class="btn btn-secondary" id="btnResetRdv">
          <i class="fa-solid fa-rotate-left"></i> Réinitialiser
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>

</div><!-- /grid -->

<script>
function openEditRdv(data) {
  document.getElementById('formRdvTitle').innerHTML =
    '<i class="fa-solid fa-calendar-pen"></i> Modifier le rendez-vous';
  document.getElementById('rdv_id').value          = data.id || '';
  document.getElementById('consultation_id').value = data.consultationId || '';
  document.getElementById('date_heure').value      = (data.date_heure||'').replace(' ','T').slice(0,16);
  document.getElementById('mode_rdv').value        = data.mode || '';
  document.getElementById('formRdv').scrollIntoView({behavior:'smooth'});
}

function submitRdvForm(e) {
  e.preventDefault();
  let valid = true;
  const c = document.getElementById('consultation_id');
  const d = document.getElementById('date_heure');
  const m = document.getElementById('mode_rdv');
  if (!c.value) { showError(c,'Sélectionner une consultation.'); valid=false; } else clearError(c);
  if (!d.value || new Date(d.value) <= new Date()) { showError(d,'Date future.'); valid=false; } else clearError(d);
  if (!m.value) { showError(m,'Sélectionner un mode.'); valid=false; } else clearError(m);
  if (valid) alert('Rendez-vous enregistré (simulation).');
}

document.getElementById('btnResetRdv').addEventListener('click', () => {
  document.getElementById('formRdvTitle').innerHTML =
    '<i class="fa-solid fa-calendar-plus"></i> Planifier un rendez-vous';
  document.getElementById('rdv_id').value = '';
  document.getElementById('formRdv').reset();
});
</script>
