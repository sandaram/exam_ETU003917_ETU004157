# 📋 Todo List & Checklist - Projet Mobile Money (S4 Info & Design)
**Créneau Livraison 1 :** Lundi (08h00 - 13h00) | **Tag Git :** `v1`
**Créneau Livraison 2 :** Lundi (13h00 - 17h10) | **Tag Git :** `v2`

---

## 📌 PHASE 1 : Initialisation & Base de Données (08h00 - 08h45)

### 🗄️ Base de Données & Configuration
- [x] **1.1 Structure SQL (Tables & Vues)**
  - **Fichier :** `base.sql`
  - **Responsable :** Olivier (0h30)
  - **Action :** Création des tables (`prefixes_operateur`, `types_operation`, `baremes_frais`, `clients`, `operations`) et des vues (`vue_situation_gains`, `vue_situation_comptes_clients`, `vue_historique_client`).
- [x] **1.2 Jeux de Données de Test**
  - **Fichier :** `base.sql`
  - **Responsable :** Sanda (0h15)
  - **Action :** Insertion des préfixes (`033`, `037`), types d'opérations, barèmes par défaut et clients initiaux.
- [x] **1.3 Configuration CodeIgniter 4**
  - **Fichiers :** `.env`, `app/Config/Database.php`, `app/Config/Routes.php`
  - **Responsables :** Olivier & Sanda (0h15)
  - **Action :** Connexion SQLite (`WRITEPATH . 'database/mobileMoney.db'`), définition de la `BaseURL` et déclaration des groupes de routes `/admin` et `/client`.

---

## 🛠️ PHASE 2 : Côté Opérateur / Administration (Olivier) — (08h45 - 11h45)

### 📡 2.1 Configuration des Préfixes (0h30)
- [x] **[Base/Modèle]** `app/Models/PrefixeModel.php`
  - Requêtes CRUD pour gérer la table `prefixes_operateur` (ajout, suppression, statut actif).
- [x] **[Intégration]** `app/Controllers/Admin/PrefixeController.php`
  - Méthodes : `index()`, `add()`, `delete($id)`.
  - **Routes :** `GET /admin/prefixes`, `POST /admin/prefixes/add`, `GET /admin/prefixes/delete/(:num)`
- [x] **[Affichage]** `app/Views/admin/prefixes/index.php`
  - Tableau de liste des préfixes + formulaire d'ajout rapide et bouton de suppression.

---

### 📊 2.2 Barèmes de Frais par Tranche (1h00)
- [x] **[Base/Modèle]** `app/Models/BaremeFraisModel.php`
  - Lecture et mise à jour des tranches de montants (`montant_min`, `montant_max`, `frais`) liées aux opérations.
- [x] **[Fonction/Logique]** Méthode d'ajustement dynamique des tarifs selon le type d'opération (`RETRAIT`, `TRANSFERT`).
- [x] **[Intégration]** `app/Controllers/Admin/BaremeController.php`
  - Méthodes : `index()`, `update()`.
  - **Routes :** `GET /admin/baremes`, `POST /admin/baremes/update`
- [x] **[Affichage]** `app/Views/admin/baremes/index.php`
  - Grille éditable des frais par tranche.

---

### 📈 2.3 Rapports & Bilan Financier (1h30)
- [x] **[Base/Modèle]** `app/Models/TransactionModel.php`
  - Requêtes d'extraction basées sur les vues `vue_situation_gains` et `vue_situation_comptes_clients`.
- [x] **[Fonction/Logique]** Calcul des cumulés des commissions perçues par l'opérateur et consolidation des soldes clients.
- [x] **[Intégration]** `app/Controllers/Admin/RapportController.php`
  - Méthodes : `gains()`, `clients()`.
  - **Routes :** `GET /admin/rapports/gains`, `GET /admin/rapports/clients`
- [x] **[Affichage]** `app/Views/admin/rapports/gains.php`
  - Tableau de bord des bénéfices/frais perçus par type d'opération.
- [x] **[Affichage]** `app/Views/admin/rapports/clients.php`
  - Liste de synthèse des comptes clients avec soldes et volumes de transactions.

---

## 📱 PHASE 3 : Côté Client (Sanda) — (08h45 - 11h45)

### 🔑 3.1 Connexion & Authentification (0h45)
- [x] **[Base/Modèle]** `app/Models/ClientModel.php`
  - Méthode `estPrefixeValide($telephone)` : vérification dans `prefixes_operateur`.
  - Méthode `connnecterOuInscrire($telephone)` : création à la volée dans `clients` si le numéro est valide mais inexistant.
- [x] **[Intégration]** `app/Controllers/Client/AuthController.php`
  - Méthodes : `login()`, `processLogin()`, `logout()`.
  - **Routes :** `GET /client/login`, `POST /client/login`, `GET /client/logout`
- [x] **[Affichage]** `app/Views/client/login.php`
  - Formulaire de saisie du numéro de téléphone + gestion des messages d'erreur de session.

---

### 💳 3.2 Espace Personnel & Dépôt (0h45)
- [x] **[Fonction/Logique]** Validation du montant du dépôt et crédit immédiat du solde client.
- [x] **[Intégration]** `app/Controllers/Client/DashboardController.php`
  - Méthodes : `index()`, `depotForm()`, `processDepot()`.
  - **Routes :** `GET /client/dashboard`, `GET /client/depot`, `POST /client/depot`
- [x] **[Affichage]** `app/Views/client/dashboard.php`
  - Carte affichant le solde courant + boutons de raccourcis vers les opérations.
- [x] **[Affichage]** `app/Views/client/depot.php`
  - Formulaire de rechargement/crédit de compte.

---

### 💸 3.3 Retrait, Transfert & Historique (1h30)
- [x] **[Base/Modèle]** `app/Models/TransactionModel.php`
  - Enregistrement dans la table `operations` (débit, crédit destinataire, écriture des frais, recalcul des soldes).
- [x] **[Fonction/Logique]**
  - Simulation et calcul automatique des frais selon les barèmes en BDD.
  - Contrôle du solde suffisant (`solde >= montant + frais`).
- [x] **[Intégration]** `app/Controllers/Client/TransactionController.php`
  - Méthodes : `retraitForm()`, `processRetrait()`, `transfertForm()`, `processTransfert()`, `historique()`.
  - **Routes :** `GET/POST /client/retrait`, `GET/POST /client/transfert`, `GET /client/historique`
- [x] **[Affichage]** `app/Views/client/retrait.php`
  - Formulaire de retrait avec estimation des frais en temps réel.
- [x] **[Affichage]** `app/Views/client/transfert.php`
  - Formulaire d'envoi vers un tiers (numéro destinataire, montant, frais calculés).
- [x] **[Affichage]** `app/Views/client/historique.php`
  - Liste chronologique de toutes les opérations du client (entrées, sorties, frais).

---

## 🧪 PHASE 4 : Recette, Merge & Livraison v1 (11h45 - 13h00)

- [x] **4.1 Tests d'Intégration & Fusion**
  - **Responsables :** Olivier & Sanda (0h45)
  - **Action :** Fusion des branches Git sur `main`, résolution de conflits, validation du parcours complet (Login ➔ Dépôt ➔ Transfert/Retrait ➔ Bilan Admin).
- [x] **4.2 Nettoyage & Documentation**
  - **Fichiers :** `base.sql`, `Taches.md`
  - **Responsables :** Olivier & Sanda (0h15)
  - **Action :** Export final d'une base propre et validation de la checklist `Taches.md`.
- [x] **4.3 Tag & Livraison Finale v1**
  - **Responsable :** Sanda (0h15)
  - **Action :** `git push origin main` et création du tag Git officiel (`git tag -a v1 -m "Livraison v1"`).

---

## 🛠️ PHASE 5 : Côté Opérateur — Interconnexion (Olivier) — (13h00 - 15h15)

> **Modèle retenu (ajusté) :** un seul opérateur géré dans l'app (le vôtre, avec login admin). Les autres opérateurs sont juste une **liste de référence** (`operateurs` : nom + commission), sans compte ni login. Un préfixe avec `operateur_id = NULL` est interne ; rempli, il est externe et rattaché à un opérateur concurrent. Deux scénarios de transfert externe à distinguer via `operations.mode_transfert` :
> - `externe_intermediaire` → montant à envoyer = `montant + frais + commission`
> - `externe_direct` → montant à envoyer = `montant + commission` (pas de frais, votre opérateur n'intervient pas dans l'acheminement)

### 🏢 5.0 Référentiel des opérateurs externes (0h25)
- [x] **[Base/SQL]** `base.sql`
  - `CREATE TABLE operateurs (id, nom, commission_pct, actif)` — pas de compte, pas de login.
  - `prefixes_operateur.operateur_id` : `NULL` = notre opérateur, rempli = autre opérateur.
  - `operations.mode_transfert`, `operateur_destinataire_id`, `numero_destinataire_externe`, `commission`.
- [x] **[Base/Modèle]** `app/Models/OperateurModel.php`
  - Liste des opérateurs actifs.
  - Logique métier : `trouverOperateurParNumero()`, `calculerCommission()`.

### 🌐 5.1 Détection réseau interne / externe (0h20)
- [x] **[Base/Modèle]** `app/Models/PrefixeModel.php` (mis à jour)
  - `allowedFields` étendu avec `operateur_id`.
  - `listAll()` fait la jointure avec `operateurs` (nom du réseau, ou "Interne" si `operateur_id` est `NULL`).
  - Nouvelle méthode `estReseauPropre(string $numero): bool` → `true` si `operateur_id IS NULL`.
- [x] **[Affichage]** `app/Views/operateur/prefixes.php`
  - Colonne "Réseau" (Interne / nom de l'opérateur externe).

### 💰 5.2 Pas d'espace séparé pour les autres opérateurs (0h10)
- [x] **[Décision métier]**
  - Les autres opérateurs sont seulement des destinations de calcul/compensation.
  - Aucun login, dashboard ou compte client n'est créé pour eux.

### 📤 5.3 Rapport : montants à envoyer à chaque opérateur (0h40)
- [x] **[Base/Modèle]** `app/Models/TransactionModel.php`
  - Méthode `calculerMontantAEnvoyer(string $modeTransfert, float $montant, float $frais, float $commission): float` — applique la formule du bon scénario.
  - Méthode `situationMontantsAEnvoyer(): array` — Query Builder en PHP, groupé par opérateur ET par scénario (`mode_transfert`).
- [x] **[Intégration]** `app/Controllers/Operateur/Rapport.php`
  - Méthode `montantsAEnvoyer()`.
  - **Route :** `GET /operateur/rapports/montants-a-envoyer`
- [x] **[Affichage]** `app/Views/operateur/montants_a_envoyer.php`
  - Tableau : opérateur / scénario (via intermédiaire ou direct) / nb opérations / montant / frais / commission / total à envoyer.

---

## 📱 PHASE 6 : Côté Client — Options d'envoi (Sanda) — (13h00 - 15h30)

> ⚠️ **Dépendance avec Olivier** : cette phase utilise `PrefixeModel::estReseauPropre()` et `OperateurModel` (`trouverOperateurParNumero()`, `calculerCommission()`) créés en Phase 5.0/5.1. **Faire un `git pull` avant de commencer**, pour ne pas dupliquer cette logique côté client.

### 🌍 6.1 Détection et calcul lors d'un transfert externe (0h40)
- [x] **[Fonction/Logique]** `app/Models/TransactionModel.php`
  - Dans le flux de transfert : appeler `PrefixeModel::estReseauPropre($numeroDestinataire)`.
    - Si `true` → transfert interne classique (barème normal, `mode_transfert = 'interne'`, comme en v1).
    - Si `false` → transfert externe : appeler `OperateurModel::trouverOperateurParNumero()` puis `calculerCommission()`.
  - Choix côté formulaire pour préciser le scénario : **via intermédiaire** ou **direct**.
    - Via intermédiaire → `frais` = barème normal (comme un transfert classique) + `commission` calculée.
    - Direct → `frais = 0` (votre opérateur n'intervient pas) + `commission` calculée.
  - Enregistrer `mode_transfert`, `operateur_destinataire_id`, `numero_destinataire_externe`, `commission` dans `operations`.
- [x] **[Intégration]** `app/Controllers/Client/TransactionController.php`
  - Adapter `processTransfert()` pour lire le numéro destinataire, détecter interne/externe, et le scénario choisi (`mode_transfert`).
  - Vérifier le solde suffisant : `solde >= montant + frais + commission` (peu importe le scénario).
- [x] **[Affichage]** `app/Views/client/transfert.php`
  - Select "Via notre opérateur" / "Direct vers l'autre opérateur" ; ignoré si le numéro est interne.

### 💸 6.2 Frais à la charge de l'expéditeur (transferts internes) (0h25)
- [ ] **[Fonction/Logique]** `app/Models/TransactionModel.php`
  - Option `inclureFrais` (bool) pour les transferts **internes** uniquement : si activé, les frais sont ajoutés au débit de l'expéditeur au lieu d'être déduits du montant reçu par le destinataire.
- [ ] **[Intégration]** `app/Controllers/Client/TransactionController.php`
  - Lire `$this->request->getPost('inclure_frais')` et ajuster le calcul du débit/crédit en conséquence.
- [ ] **[Affichage]** `app/Views/client/transfert.php`
  - Case à cocher "Inclure les frais de retrait lors de l'envoi" (visible uniquement pour un transfert interne).

### 👥 6.3 Envoi multiple vers plusieurs numéros (0h55)
- [ ] **[Fonction/Logique]** `app/Models/TransactionModel.php`
  - Méthode `transfertMultiple(array $numeros, float $montantTotal, ?bool $viaIntermediaire = null): array` — divise `$montantTotal` par le nombre de destinataires puis exécute un transfert par numéro (chacun peut être interne ou externe, détecté indépendamment via `estReseauPropre()`).
- [ ] **[Intégration]** `app/Controllers/Client/TransactionController.php`
  - Méthode `processTransfertMultiple()`.
  - **Route :** `POST /client/transfert/multiple`
- [ ] **[Affichage]** `app/Views/client/transfert.php`
  - Champ dynamique pour ajouter plusieurs numéros destinataires + aperçu du montant réparti (et du scénario détecté) par numéro.

---

## 🧪 PHASE 7 : Recette, Merge & Livraison v2 (15h30 - 17h10)

- [x] **7.1 Fusion de `ajout_operateurs.sql` dans `base.sql`**
  - **Responsable :** Olivier (0h15)
  - **Action :** Intégrer le script incrémental dans le `base.sql` principal (nouvelle section "Livraison 2"), pour que la base soit reconstructible en un seul script depuis zéro.
- [ ] **7.2 Tests d'Intégration & Fusion**
  - **Responsables :** Olivier & Sanda (0h35)
  - **Action :** Fusion des branches Git sur `main`, résolution de conflits, validation du parcours complet : transfert interne, transfert externe via intermédiaire, transfert externe direct, rapport des montants à envoyer.
- [ ] **7.3 Nettoyage & Documentation**
  - **Fichiers :** `base.sql`, `Taches.md`
  - **Responsables :** Olivier & Sanda (0h10)
  - **Action :** Export final d'une base propre et validation de la checklist `Taches.md`.
- [ ] **7.4 Tag & Livraison Finale v2**
  - **Responsable :** Sanda (0h15)
  - **Action :** `git push origin main` et création du tag Git officiel (`git tag -a v2 -m "Livraison v2"`).

---

## ⚠️ Point de vigilance avant la v2

Le sujet évoque des routes `/operateur/*` dans certains échanges récents (contrôleurs `App\Controllers\Operateur\...`), alors que ce `Taches.md` documente `/admin/*` (`App\Controllers\Admin\...`) depuis la v1. **Choisissez une seule convention avec votre binôme avant de continuer**, sinon vous aurez des routes qui pointent vers des contrôleurs inexistants (comme le 404 rencontré sur `/operateur/login`).
