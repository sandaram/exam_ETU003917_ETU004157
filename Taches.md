# 📋 Todo List & Checklist - Projet Mobile Money (S4 Info & Design)
**Créneau Livraison 1 :** Lundi (08h00 - 13h00) | **Tag Git :** `v1`

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

## 🧪 PHASE 4 : Recette, Merge & Livraison (11h45 - 13h00)

- [ ] **4.1 Tests d'Intégration & Fusion**
  - **Responsables :** Olivier & Sanda (0h45)
  - **Action :** Fusion des branches Git sur `main`, résolution de conflits, validation du parcours complet (Login ➔ Dépôt ➔ Transfert/Retrait ➔ Bilan Admin).
- [ ] **4.2 Nettoyage & Documentation**
  - **Fichiers :** `base.sql`, `Taches.md`
  - **Responsables :** Olivier & Sanda (0h15)
  - **Action :** Export final d'une base propre et validation de la checklist `Taches.md`.
- [ ] **4.3 Tag & Livraison Finale**
  - **Responsable :** Sanda (0h15)
  - **Action :** `git push origin main` et création du tag Git officiel (`git tag -a v1 -m "Livraison v1"`).
