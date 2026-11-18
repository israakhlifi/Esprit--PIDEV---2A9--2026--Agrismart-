<?php
/**
 * app/Views/pages/experts.php
 * Variables reçues : $experts (array)
 */
?>

<!-- ── Toolbar ─────────────────────────────────────────── -->
<div class="toolbar">
  <div class="search-box" style="flex:1">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="searchExpert" class="form-control"
           placeholder="Rechercher par nom, spécialité, région…"
           oninput="filterExperts(this.value)">
  </div>
  <button class="btn btn-primary" onclick="openAddExpert()">
    <i class="fa-solid fa-plus"></i> Ajouter un expert
  </button>
</div>

<!-- ── Tableau ─────────────────────────────────────────── -->
<div class="card">
  <div class="card-header">
    <h2><i class="fa-solid fa-user-tie"></i> Liste des experts</h2>
    <span style="font-size:.8rem;color:var(--gris)" id="expertCount">
      <?= count($experts) ?> résultat(s)
    </span>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Nom</th><th>Spécialité</th>
          <th>Région</th><th>Disponibilité</th><th>Actions</th>
        </tr>
      </thead>
      <tbody id="expertTableBody">
        <?php foreach ($experts as $e) : ?>
          <tr data-search="<?= htmlspecialchars(strtolower($e['nom'].' '.$e['specialite'].' '.$e['region'])) ?>">
            <td><?= (int)$e['id'] ?></td>
            <td><?= htmlspecialchars($e['nom']) ?></td>
            <td><?= htmlspecialchars($e['specialite']) ?></td>
            <td><?= htmlspecialchars($e['region']) ?></td>
            <td>
              <?php if ($e['disponible']) : ?>
                <span class="badge badge-dispo">Disponible</span>
              <?php else : ?>
                <span class="badge badge-indispo">Indisponible</span>
              <?php endif; ?>
            </td>
            <td>
              <button class="btn btn-outline btn-sm"
                onclick="openEditExpert(<?= htmlspecialchars(json_encode([
                  'id'         => $e['id'],
                  'nom'        => $e['nom'],
                  'specialite' => $e['specialite'],
                  'region'     => $e['region'],
                  'bio'        => $e['bio'] ?? '',
                  'disponible' => $e['disponible'],
                ])) ?>)">
                <i class="fa-solid fa-pen"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm"
                onclick="confirmDelete('index.php?page=expert_delete&id=<?= (int)$e['id'] ?>','<?= htmlspecialchars(addslashes($e['nom'])) ?>')">
                <i class="fa-solid fa-trash"></i> Supprimer
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination démo -->
  <div class="pagination">
    <button class="page-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
    <button class="page-btn active">1</button>
    <button class="page-btn">2</button>
    <button class="page-btn"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
</div>

<!-- ══ Modal Ajouter / Modifier ══════════════════════════ -->
<div class="modal-overlay" id="modalExpert">
  <div class="modal-box">
    <div class="modal-header">
      <h3 id="modalExpertTitle"><i class="fa-solid fa-user-tie"></i> Ajouter un expert</h3>
      <button class="modal-close" data-modal-close="modalExpert">&times;</button>
    </div>
    <form id="formExpert" onsubmit="submitExpertForm(event)">
      <div class="modal-body">
        <input type="hidden" name="expert_id" id="expertId">

        <div class="form-group">
          <label class="form-label" for="expertNom">
            Nom complet <span style="color:red">*</span>
          </label>
          <input type="text" id="expertNom" name="nom" class="form-control"
                 placeholder="Ex : Dr. Ahmed Ben Salah" minlength="3" maxlength="120">
          <div class="invalid-feedback">Au moins 3 caractères.</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="expertSpec">
            Spécialité <span style="color:red">*</span>
          </label>
          <input type="text" id="expertSpec" name="specialite" class="form-control"
                 placeholder="Ex : Phytopathologie" minlength="3" maxlength="100">
          <div class="invalid-feedback">3 à 100 caractères.</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="expertRegion">
            Région <span style="color:red">*</span>
          </label>
          <input type="text" id="expertRegion" name="region" class="form-control"
                 placeholder="Ex : Tunis" minlength="2" maxlength="100">
          <div class="invalid-feedback">2 à 100 caractères.</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="expertBio">Bio (optionnel)</label>
          <textarea id="expertBio" name="bio" class="form-control"
                    rows="3" maxlength="1000"
                    placeholder="Courte présentation…"></textarea>
          <div class="invalid-feedback">Maximum 1000 caractères.</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="expertDispo">
            Disponibilité <span style="color:red">*</span>
          </label>
          <select id="expertDispo" name="disponible" class="form-control">
            <option value="1">Disponible</option>
            <option value="0">Indisponible</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close="modalExpert">Annuler</button>
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Enregistrer
        </button>
      </div>
    </form>
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

<script>
function openAddExpert() {
  document.getElementById('modalExpertTitle').innerHTML =
    '<i class="fa-solid fa-user-tie"></i> Ajouter un expert';
  document.getElementById('expertId').value = '';
  ['expertNom','expertSpec','expertRegion','expertBio'].forEach(id => {
    document.getElementById(id).value = '';
    document.getElementById(id).classList.remove('is-invalid','is-valid');
  });
  document.getElementById('expertDispo').value = '1';
  openModal('modalExpert');
}

function openEditExpert(data) {
  document.getElementById('modalExpertTitle').innerHTML =
    '<i class="fa-solid fa-user-tie"></i> Modifier un expert';
  document.getElementById('expertId').value      = data.id || '';
  document.getElementById('expertNom').value     = data.nom || '';
  document.getElementById('expertSpec').value    = data.specialite || '';
  document.getElementById('expertRegion').value  = data.region || '';
  document.getElementById('expertBio').value     = data.bio || '';
  document.getElementById('expertDispo').value   = data.disponible != null ? data.disponible : '1';
  ['expertNom','expertSpec','expertRegion','expertBio','expertDispo'].forEach(id =>
    document.getElementById(id).classList.remove('is-invalid','is-valid')
  );
  openModal('modalExpert');
}

function submitExpertForm(e) {
  e.preventDefault();
  let valid = true;
  const nom    = document.getElementById('expertNom');
  const spec   = document.getElementById('expertSpec');
  const region = document.getElementById('expertRegion');
  const bio    = document.getElementById('expertBio');

  if (nom.value.trim().length < 3)                       { showError(nom,'Min. 3 caractères.');    valid=false; } else clearError(nom);
  if (spec.value.trim().length < 3 || spec.value.trim().length > 100) { showError(spec,'3–100 caractères.'); valid=false; } else clearError(spec);
  if (region.value.trim().length < 2 || region.value.trim().length > 100) { showError(region,'2–100 caractères.'); valid=false; } else clearError(region);
  if (bio.value.trim().length > 1000)                    { showError(bio,'Max. 1000 car.');        valid=false; } else clearError(bio);

  if (valid) { alert('Expert enregistré.'); closeModal('modalExpert'); }
}

function filterExperts(val) {
  const rows  = document.querySelectorAll('#expertTableBody tr');
  let   count = 0;
  rows.forEach(row => {
    const match = row.dataset.search.includes(val.toLowerCase());
    row.style.display = match ? '' : 'none';
    if (match) count++;
  });
  document.getElementById('expertCount').textContent = count + ' résultat(s)';
}
</script>
