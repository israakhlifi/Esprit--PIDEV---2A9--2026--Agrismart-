/**
 * AgriSmart — admin-forms.js
 * Validation JS commune + comportements UI (sidebar, modaux, filtres)
 * Miroir des règles PHP du brief Membre 2.
 */

/* ══════════════════════════════════════════════════════════════
   1. UTILITAIRES
══════════════════════════════════════════════════════════════ */
function showError(field, msg) {
  field.classList.remove('is-valid');
  field.classList.add('is-invalid');
  const fb = field.nextElementSibling;
  if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = msg;
}
function clearError(field) {
  field.classList.remove('is-invalid');
  field.classList.add('is-valid');
  const fb = field.nextElementSibling;
  if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = '';
}
function resetField(field) {
  field.classList.remove('is-invalid', 'is-valid');
  const fb = field.nextElementSibling;
  if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = '';
}

function today() {
  return new Date().toISOString().split('T')[0];
}
function nowDatetime() {
  const d = new Date();
  d.setSeconds(0, 0);
  return d.toISOString().slice(0, 16);
}

/* ══════════════════════════════════════════════════════════════
   2. SIDEBAR TOGGLE (responsive)
══════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  const sidebar   = document.querySelector('.sidebar');
  const toggle    = document.querySelector('.sidebar-toggle');
  const backdrop  = document.querySelector('.sidebar-backdrop');

  if (toggle && sidebar) {
    toggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      backdrop && backdrop.classList.toggle('open');
    });
  }
  if (backdrop) {
    backdrop.addEventListener('click', () => {
      sidebar.classList.remove('open');
      backdrop.classList.remove('open');
    });
  }

  /* ── Modaux : ouvrir / fermer ── */
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.modalOpen;
      openModal(id);
    });
  });
  document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.modalClose;
      closeModal(id);
    });
  });
  document.querySelectorAll('.modal-overlay').forEach(ov => {
    ov.addEventListener('click', e => {
      if (e.target === ov) closeModal(ov.id);
    });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(ov => closeModal(ov.id));
    }
  });

  /* ── Compteurs de caractères ── */
  document.querySelectorAll('[data-char-counter]').forEach(textarea => {
    const max = parseInt(textarea.getAttribute('maxlength') || textarea.dataset.max || 3000);
    const counterId = textarea.dataset.charCounter;
    const counter = document.getElementById(counterId);
    if (!counter) return;
    const update = () => {
      const len = textarea.value.length;
      counter.textContent = `${len} / ${max} caractères`;
      counter.classList.toggle('warn', len > max * 0.9);
    };
    textarea.addEventListener('input', update);
    update();
  });

  /* ── Filtre statut consultation ── */
  const filterSelect = document.getElementById('filterStatut');
  if (filterSelect) {
    filterSelect.addEventListener('change', () => {
      const val = filterSelect.value;
      const rows = document.querySelectorAll('tbody tr[data-statut]');
      rows.forEach(row => {
        row.style.display = (!val || row.dataset.statut === val) ? '' : 'none';
      });
    });
  }

  /* ── Validation formulaires ── */
  initExpertForm();
  initConsultationForm();
  initRdvForm();
  initConseilForm();
});

/* ══════════════════════════════════════════════════════════════
   3. MODAL HELPERS
══════════════════════════════════════════════════════════════ */
function openModal(id) {
  const ov = document.getElementById(id);
  if (ov) { ov.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
  const ov = document.getElementById(id);
  if (ov) { ov.classList.remove('open'); document.body.style.overflow = ''; }
}

/* ══════════════════════════════════════════════════════════════
   4. MODALE CONFIRMATION SUPPRESSION
══════════════════════════════════════════════════════════════ */
function confirmDelete(url, label) {
  const ov = document.getElementById('modalConfirmDelete');
  if (!ov) return;
  const msg = ov.querySelector('#confirmDeleteMsg');
  const form = ov.querySelector('#confirmDeleteForm');
  if (msg) msg.textContent = `Supprimer « ${label} » ? Cette action est irréversible.`;
  if (form) form.action = url;
  openModal('modalConfirmDelete');
}

/* ══════════════════════════════════════════════════════════════
   5. REMPLISSAGE MODALE MODIFIER (experts)
   Le champ utilisateur est maintenant un texte libre (nom).
══════════════════════════════════════════════════════════════ */
function openEditExpert(data) {
  const ov = document.getElementById('modalExpert');
  if (!ov) return;
  ov.querySelector('#modalExpertTitle').innerHTML =
    '<i class="fa-solid fa-user-tie"></i> Modifier un expert';
  const idField = ov.querySelector('[name="expert_id"]');
  if (idField) idField.value = data.id || '';
  const nomField = ov.querySelector('[name="nom"]');
  if (nomField) nomField.value = data.nom || data.userId || '';
  const specField = ov.querySelector('[name="specialite"]');
  if (specField) specField.value = data.specialite || '';
  const regField = ov.querySelector('[name="region"]');
  if (regField) regField.value = data.region || '';
  const bioField = ov.querySelector('[name="bio"]');
  if (bioField) bioField.value = data.bio || '';
  const dispoField = ov.querySelector('[name="disponible"]');
  if (dispoField) dispoField.value = data.disponible != null ? data.disponible : '1';
  ov.querySelectorAll('.form-control').forEach(resetField);
  openModal('modalExpert');
}

/* ══════════════════════════════════════════════════════════════
   6. MODALE DÉTAIL CONSULTATION
══════════════════════════════════════════════════════════════ */
function openConsultationDetail(data) {
  const ov = document.getElementById('modalConsultDetail');
  if (!ov) return;
  ov.querySelector('#dAgriculteur').textContent  = data.agriculteur || '-';
  ov.querySelector('#dExpert').textContent       = data.expert      || '-';
  ov.querySelector('#dSujet').textContent        = data.sujet       || '-';
  ov.querySelector('#dDate').textContent         = data.date        || '-';
  ov.querySelector('#dDescription').textContent  = data.description || '-';
  const badge = ov.querySelector('#dStatut');
  badge.textContent = data.statut || '-';
  badge.className = 'badge badge-' + (data.statut || '');
  openModal('modalConsultDetail');
}

/* ══════════════════════════════════════════════════════════════
   7. VALIDATION — FORMULAIRE EXPERT
══════════════════════════════════════════════════════════════ */
function initExpertForm() {
  const form = document.getElementById('formExpert');
  if (!form) return;

  form.addEventListener('submit', e => {
    let valid = true;

    const nom        = form.querySelector('[name="nom"]');
    const specialite = form.querySelector('[name="specialite"]');
    const region     = form.querySelector('[name="region"]');
    const bio        = form.querySelector('[name="bio"]');
    const disponible = form.querySelector('[name="disponible"]');

    if (nom) {
      const v = nom.value.trim();
      if (v.length < 3 || v.length > 120) {
        showError(nom, 'Le nom doit contenir entre 3 et 120 caractères.'); valid = false;
      } else clearError(nom);
    }

    if (specialite) {
      const v = specialite.value.trim();
      if (v.length < 3 || v.length > 100) {
        showError(specialite, 'La spécialité doit contenir entre 3 et 100 caractères.'); valid = false;
      } else clearError(specialite);
    }

    if (region) {
      const v = region.value.trim();
      if (v.length < 2 || v.length > 100) {
        showError(region, 'La région doit contenir entre 2 et 100 caractères.'); valid = false;
      } else clearError(region);
    }

    if (bio && bio.value.trim().length > 1000) {
      showError(bio, 'La bio ne doit pas dépasser 1000 caractères.'); valid = false;
    } else if (bio) clearError(bio);

    if (disponible && disponible.value !== '0' && disponible.value !== '1') {
      showError(disponible, 'Valeur invalide.'); valid = false;
    } else if (disponible) clearError(disponible);

    if (!valid) e.preventDefault();
  });
}

/* ══════════════════════════════════════════════════════════════
   8. VALIDATION — FORMULAIRE CONSULTATION (statut)
══════════════════════════════════════════════════════════════ */
function initConsultationForm() {
  /* Formulaire de création/modification statut côté admin — minimal */
  const forms = document.querySelectorAll('.form-statut-consult');
  forms.forEach(form => {
    form.addEventListener('submit', e => {
      const statut = form.querySelector('[name="statut"]');
      const allowed = ['en_attente', 'acceptee', 'refusee', 'terminee'];
      if (statut && !allowed.includes(statut.value)) {
        showError(statut, 'Statut invalide.'); e.preventDefault();
      }
    });
  });
}

/* ══════════════════════════════════════════════════════════════
   9. VALIDATION — FORMULAIRE RENDEZ-VOUS
══════════════════════════════════════════════════════════════ */
function initRdvForm() {
  const form = document.getElementById('formRdv');
  if (!form) return;

  /* Bloquer dates passées à la saisie */
  const dtInput = form.querySelector('[name="date_heure"]');
  if (dtInput) {
    dtInput.setAttribute('min', nowDatetime());
  }

  form.addEventListener('submit', e => {
    let valid = true;

    const consultId = form.querySelector('[name="consultation_id"]');
    const dateHeure = form.querySelector('[name="date_heure"]');
    const mode      = form.querySelector('[name="mode"]');

    if (consultId && !consultId.value) {
      showError(consultId, 'Veuillez sélectionner une consultation.'); valid = false;
    } else if (consultId) clearError(consultId);

    if (dateHeure) {
      const val = dateHeure.value;
      if (!val || new Date(val) <= new Date()) {
        showError(dateHeure, 'La date et l\'heure doivent être dans le futur.'); valid = false;
      } else clearError(dateHeure);
    }

    const modeAllowed = ['en_ligne', 'sur_place'];
    if (mode && !modeAllowed.includes(mode.value)) {
      showError(mode, 'Mode invalide.'); valid = false;
    } else if (mode) clearError(mode);

    if (!valid) e.preventDefault();
  });
}

/* ══════════════════════════════════════════════════════════════
   10. VALIDATION — FORMULAIRE CONSEIL
══════════════════════════════════════════════════════════════ */
function initConseilForm() {
  const form = document.getElementById('formConseil');
  if (!form) return;

  form.addEventListener('submit', e => {
    let valid = true;

    const consultId = form.querySelector('[name="consultation_id"]');
    const contenu   = form.querySelector('[name="contenu"]');

    if (consultId && !consultId.value) {
      showError(consultId, 'Veuillez sélectionner une consultation.'); valid = false;
    } else if (consultId) clearError(consultId);

    if (contenu) {
      const len = contenu.value.trim().length;
      if (len < 20) {
        showError(contenu, 'Le contenu doit contenir au moins 20 caractères.'); valid = false;
      } else if (len > 3000) {
        showError(contenu, 'Le contenu ne doit pas dépasser 3000 caractères.'); valid = false;
      } else clearError(contenu);
    }

    if (!valid) e.preventDefault();
  });
}

/* ══════════════════════════════════════════════════════════════
   11. VALIDATION — FORMULAIRE INSCRIPTION / DATE SOUHAITÉE
══════════════════════════════════════════════════════════════ */
(function lockPastDates() {
  document.querySelectorAll('[name="date_souhaitee"]').forEach(inp => {
    inp.setAttribute('min', today());
    inp.addEventListener('change', () => {
      if (inp.value < today()) {
        showError(inp, 'La date doit être aujourd\'hui ou dans le futur.');
      } else {
        clearError(inp);
      }
    });
  });
  document.querySelectorAll('[name="date_heure"]').forEach(inp => {
    inp.setAttribute('min', nowDatetime());
  });
})();
