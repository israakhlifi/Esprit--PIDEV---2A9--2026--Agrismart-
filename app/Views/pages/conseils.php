<?php
/**
 * app/Views/pages/conseils.php
 * Variables reçues : $conseils, $consultations, $errors, $old
 */
function truncate80(string $s): string {
  return mb_strlen($s) > 80 ? mb_substr($s,0,80).'…' : $s;
}
?>

<div style="display:grid;grid-template-columns:1fr 400px;gap:1.5rem;align-items:start">

  <!-- ── Tableau conseils ──────────────────────────────── -->
  <div class="card">
    <div class="card-header">
      <h2><i class="fa-solid fa-lightbulb"></i> Conseils publiés</h2>
      <span style="font-size:.8rem;color:var(--gris)"><?= count($conseils) ?> conseil(s)</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Consultation</th><th>Expert</th>
            <th>Extrait</th><th>Date</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($conseils as $con) : ?>
            <tr>
              <td><?= (int)$con['id'] ?></td>
              <td><?= htmlspecialchars($con['consultation']??'-') ?></td>
              <td><?= htmlspecialchars($con['expert']??'-') ?></td>
              <td style="max-width:260px;color:var(--gris)">
                <?= htmlspecialchars(truncate80($con['contenu']??'')) ?>
              </td>
              <td><?= htmlspecialchars($con['created_at']??'-') ?></td>
              <td style="white-space:nowrap">
                <button class="btn btn-outline btn-sm"
                  onclick="openEditConseil(<?= htmlspecialchars(json_encode([
                    'id'            => $con['id'],
                    'consultationId'=> $con['consultationId']??'',
                    'contenu'       => $con['contenu'],
                  ])) ?>)">
                  <i class="fa-solid fa-pen"></i>
                </button>
                <button class="btn btn-danger btn-sm"
                  onclick="confirmDelete('#','Conseil #<?= (int)$con['id'] ?>')">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ── Formulaire rédaction ──────────────────────────── -->
  <div class="card">
    <div class="card-header">
      <h2 id="formConseilTitle"><i class="fa-solid fa-pen-nib"></i> Rédiger un conseil</h2>
    </div>
    <form id="formConseil" onsubmit="submitConseilForm(event)" class="modal-body">
      <input type="hidden" name="conseil_id" id="conseil_id" value="">

      <div class="form-group">
        <label class="form-label">Consultation <span style="color:red">*</span></label>
        <select name="consultation_id" id="consultation_id_conseil" class="form-control">
          <option value="">— Sélectionner —</option>
          <?php foreach ($consultations as $co) : ?>
            <option value="<?= (int)$co['id'] ?>"
              <?= (($old['consultation_id']??'')==$co['id'])?'selected':'' ?>>
              <?= htmlspecialchars($co['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Sélectionner une consultation.</div>
      </div>

      <div class="form-group">
        <label class="form-label">
          Contenu <span style="color:red">*</span>
          <small style="font-weight:400;color:var(--gris)">(20–3000 car.)</small>
        </label>
        <textarea name="contenu" id="contenu" rows="8" class="form-control"
                  maxlength="3000"
                  data-char-counter="contenuCounter"
                  data-max="3000"
                  placeholder="Rédigez votre conseil agronomique…"><?= htmlspecialchars($old['contenu']??'') ?></textarea>
        <div class="invalid-feedback">Minimum 20 caractères, maximum 3000.</div>
        <div class="char-counter" id="contenuCounter">0 / 3000 caractères</div>
      </div>

      <div style="display:flex;gap:.75rem;justify-content:flex-end;padding-top:.5rem">
        <button type="button" class="btn btn-secondary" id="btnResetConseil">
          <i class="fa-solid fa-rotate-left"></i> Réinitialiser
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Publier
        </button>
      </div>
    </form>
  </div>

</div><!-- /grid -->

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

<script>
function openEditConseil(data) {
  document.getElementById('formConseilTitle').innerHTML =
    '<i class="fa-solid fa-pen-nib"></i> Modifier le conseil';
  document.getElementById('conseil_id').value                = data.id || '';
  document.getElementById('consultation_id_conseil').value   = data.consultationId || '';
  document.getElementById('contenu').value                   = data.contenu || '';
  document.getElementById('contenu').dispatchEvent(new Event('input'));
  document.getElementById('formConseil').scrollIntoView({behavior:'smooth'});
}

function submitConseilForm(e) {
  e.preventDefault();
  let valid = true;
  const c = document.getElementById('consultation_id_conseil');
  const t = document.getElementById('contenu');
  if (!c.value)                                        { showError(c,'Sélectionner une consultation.'); valid=false; } else clearError(c);
  if (t.value.trim().length < 20)                      { showError(t,'Min. 20 caractères.');            valid=false; }
  else if (t.value.trim().length > 3000)               { showError(t,'Max. 3000 caractères.');          valid=false; }
  else clearError(t);
  if (valid) alert('Conseil publié (simulation).');
}

document.getElementById('btnResetConseil').addEventListener('click', () => {
  document.getElementById('formConseilTitle').innerHTML =
    '<i class="fa-solid fa-pen-nib"></i> Rédiger un conseil';
  document.getElementById('conseil_id').value = '';
  document.getElementById('formConseil').reset();
  document.getElementById('contenu').dispatchEvent(new Event('input'));
});
</script>
