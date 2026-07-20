<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= base_url('client/dashboard') ?>">Mobile Money</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Afficher le menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="clientNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('client/dashboard') ?>">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('client/depot') ?>">Depot</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('client/retrait') ?>">Retrait</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('client/transfert') ?>">Transfert</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('client/historique') ?>">Historique</a></li>
            </ul>
            <span class="navbar-text me-3"><?= esc(session()->get('client_phone') ?? '') ?></span>
            <a class="btn btn-outline-light btn-sm" href="<?= base_url('client/logout') ?>">Deconnexion</a>
        </div>
    </div>
</nav>
