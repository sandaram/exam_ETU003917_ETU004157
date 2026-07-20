<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= base_url('operateur/prefixes') ?>">Espace Operateur</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#operateurNavbar" aria-controls="operateurNavbar" aria-expanded="false" aria-label="Afficher le menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="operateurNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur/prefixes') ?>">Prefixes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur/baremes') ?>">Baremes</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur/rapports/gains') ?>">Gains</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur/rapports/comptes') ?>">Comptes clients</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur/rapports/montants-a-envoyer') ?>">Autres operateurs</a></li>
            </ul>
            <span class="navbar-text me-3"><?= esc(session()->get('operateur_nom') ?? 'operateur') ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?= base_url('operateur/logout') ?>">Deconnexion</a>
        </div>
    </div>
</nav>
