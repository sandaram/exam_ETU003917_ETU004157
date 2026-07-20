<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/client_navbar') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Faire un transfert</h3>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('client/transfert') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="type_envoi" class="form-label">Mode d'envoi</label>
                <select class="form-select" id="type_envoi" name="type_envoi">
                    <option value="simple" <?= old('type_envoi', 'simple') === 'simple' ? 'selected' : '' ?>>Un seul numero</option>
                    <option value="multiple" <?= old('type_envoi') === 'multiple' ? 'selected' : '' ?>>Plusieurs numeros du meme operateur</option>
                </select>
            </div>
            <div class="mb-3" id="mode_transfert_group">
                <label for="mode_transfert" class="form-label">Type de transfert</label>
                <select class="form-select" id="mode_transfert" name="mode_transfert">
                    <option value="interne" <?= old('mode_transfert', 'interne') === 'interne' ? 'selected' : '' ?>>Même operateur : montant + frais</option>
                    <option value="autre_operateur" <?= old('mode_transfert') === 'autre_operateur' ? 'selected' : '' ?>>Autre operateur : montant + frais + commission</option>
                </select>
            </div>
            <div class="mb-3" id="telephone_simple_group">
                <label for="telephone_destinataire" class="form-label">Numero destinataire</label>
                <input type="text" class="form-control" id="telephone_destinataire" name="telephone_destinataire" value="<?= old('telephone_destinataire') ?>" placeholder="Ex: 0372345678" required autofocus>
            </div>
            <div class="mb-3 d-none" id="telephones_multiples_group">
                <label for="telephones_destinataires" class="form-label">Numeros destinataires</label>
                <textarea class="form-control" id="telephones_destinataires" name="telephones_destinataires" rows="4" placeholder="Un numero par ligne, ou separes par virgule"><?= old('telephones_destinataires') ?></textarea>
                <div class="form-text">Le montant sera divise automatiquement pour chaque numero. Même operateur uniquement.</div>
            </div>
            <div class="mb-3" id="operateur_destinataire_group">
                <label for="operateur_destinataire_id" class="form-label">Operateur destinataire</label>
                <select class="form-select" id="operateur_destinataire_id" name="operateur_destinataire_id">
                    <option value="">Choisir un operateur</option>
                    <?php foreach ($operateurs as $operateur): ?>
                        <option
                            value="<?= esc($operateur['id']) ?>"
                            data-commission="<?= esc($operateur['commission_pct']) ?>"
                            <?= (string) old('operateur_destinataire_id') === (string) $operateur['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($operateur['nom']) ?> - <?= esc($operateur['commission_pct']) ?>%
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">La commission est calculee selon l'operateur choisi.</div>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" min="1" step="1" class="form-control" id="montant" name="montant" value="<?= old('montant') ?>" required>
                <div class="form-text">Pour un transfert même operateur, le numero doit utiliser un prefixe interne actif.</div>
            </div>
            <div class="form-check form-switch mb-3" id="inclure_frais_group">
                <input class="form-check-input" type="checkbox" role="switch" id="inclure_frais_retrait" name="inclure_frais_retrait" value="1" <?= old('inclure_frais_retrait') ? 'checked' : '' ?>>
                <label class="form-check-label" for="inclure_frais_retrait">Inclure les frais de retrait dans l'envoi</label>
                <div class="form-text">Disponible uniquement pour le même operateur. Il n'y a pas de frais de retrait pour les autres operateurs.</div>
            </div>
            <div class="alert alert-info" id="apercu_transfert">
                <div class="fw-semibold mb-1">Apercu du montant a payer</div>
                <div id="apercu_lignes">Saisissez un montant pour voir le total.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
        </form>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    const baremesTransfert = <?= json_encode($baremesTransfert, JSON_NUMERIC_CHECK) ?>;
    const baremesRetrait = <?= json_encode($baremesRetrait, JSON_NUMERIC_CHECK) ?>;
    const typeEnvoi = document.getElementById('type_envoi');
    const modeTransfert = document.getElementById('mode_transfert');
    const modeTransfertGroup = document.getElementById('mode_transfert_group');
    const telephoneSimpleGroup = document.getElementById('telephone_simple_group');
    const telephoneSimple = document.getElementById('telephone_destinataire');
    const telephonesMultiplesGroup = document.getElementById('telephones_multiples_group');
    const telephonesMultiples = document.getElementById('telephones_destinataires');
    const operateurGroup = document.getElementById('operateur_destinataire_group');
    const operateurSelect = document.getElementById('operateur_destinataire_id');
    const montantInput = document.getElementById('montant');
    const inclureFraisGroup = document.getElementById('inclure_frais_group');
    const inclureFraisRetrait = document.getElementById('inclure_frais_retrait');
    const apercuLignes = document.getElementById('apercu_lignes');

    function montantFormat(value) {
        return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value) + ' Ar';
    }

    function calculerFrais(baremes, montant) {
        const bareme = baremes.find((ligne) => montant >= Number(ligne.montant_min) && montant <= Number(ligne.montant_max));
        return bareme ? Number(bareme.frais) : null;
    }

    function numerosMultiples() {
        return telephonesMultiples.value.split(/[\s,;]+/).map((numero) => numero.trim()).filter(Boolean);
    }

    function toggleOperateurDestinataire() {
        const isMultiple = typeEnvoi.value === 'multiple';
        const isAutreOperateur = !isMultiple && modeTransfert.value === 'autre_operateur';

        modeTransfertGroup.classList.toggle('d-none', isMultiple);
        telephoneSimpleGroup.classList.toggle('d-none', isMultiple);
        telephonesMultiplesGroup.classList.toggle('d-none', !isMultiple);
        operateurGroup.classList.toggle('d-none', !isAutreOperateur);
        inclureFraisGroup.classList.toggle('d-none', isAutreOperateur);

        telephoneSimple.required = !isMultiple;
        telephonesMultiples.required = isMultiple;
        operateurSelect.required = isAutreOperateur;

        if (isMultiple) {
            modeTransfert.value = 'interne';
        }

        if (isAutreOperateur) {
            inclureFraisRetrait.checked = false;
        }

        afficherApercu();
    }

    function afficherApercu() {
        const montant = Number(montantInput.value || 0);

        if (montant <= 0) {
            apercuLignes.textContent = 'Saisissez un montant pour voir le total.';
            return;
        }

        const isMultiple = typeEnvoi.value === 'multiple';
        const isAutreOperateur = !isMultiple && modeTransfert.value === 'autre_operateur';
        const nombreDestinataires = isMultiple ? numerosMultiples().length : 1;
        const montantParNumero = isMultiple && nombreDestinataires > 0 ? montant / nombreDestinataires : montant;
        const fraisTransfert = calculerFrais(baremesTransfert, montantParNumero);

        if (fraisTransfert === null) {
            apercuLignes.textContent = 'Montant hors bareme.';
            return;
        }

        const fraisRetrait = !isAutreOperateur && inclureFraisRetrait.checked ? calculerFrais(baremesRetrait, montantParNumero) : 0;

        if (fraisRetrait === null) {
            apercuLignes.textContent = 'Frais de retrait hors bareme.';
            return;
        }

        const option = operateurSelect.selectedOptions[0];
        const commissionPct = isAutreOperateur && option ? Number(option.dataset.commission || 0) : 0;
        const commission = isAutreOperateur ? montant * commissionPct / 100 : 0;
        const totalFraisTransfert = fraisTransfert * nombreDestinataires;
        const totalFraisRetrait = Number(fraisRetrait) * nombreDestinataires;
        const totalAPayer = montant + totalFraisTransfert + totalFraisRetrait + commission;
        const montantAEnvoyer = isAutreOperateur ? montant + commission : montant + totalFraisRetrait;

        apercuLignes.innerHTML = [
            `Montant saisi : <strong>${montantFormat(montant)}</strong>`,
            isMultiple ? `Nombre de destinataires : <strong>${nombreDestinataires || 0}</strong>` : '',
            isMultiple && nombreDestinataires > 0 ? `Part par numero : <strong>${montantFormat(montantParNumero)}</strong>` : '',
            `Frais de transfert : <strong>${montantFormat(totalFraisTransfert)}</strong>`,
            totalFraisRetrait > 0 ? `Frais de retrait inclus : <strong>${montantFormat(totalFraisRetrait)}</strong>` : '',
            commission > 0 ? `Commission autre operateur : <strong>${montantFormat(commission)}</strong>` : '',
            `Montant envoye au destinataire : <strong>${montantFormat(montantAEnvoyer)}</strong>`,
            `Total a payer : <strong>${montantFormat(totalAPayer)}</strong>`
        ].filter(Boolean).map((ligne) => `<div>${ligne}</div>`).join('');
    }

    typeEnvoi.addEventListener('change', toggleOperateurDestinataire);
    modeTransfert.addEventListener('change', toggleOperateurDestinataire);
    operateurSelect.addEventListener('change', afficherApercu);
    montantInput.addEventListener('input', afficherApercu);
    inclureFraisRetrait.addEventListener('change', afficherApercu);
    telephonesMultiples.addEventListener('input', afficherApercu);
    toggleOperateurDestinataire();
</script>
</body>
</html>
