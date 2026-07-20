# Répartition des Tâches - Projet Mobile Money (S4 Info & Design)
**Créneau Livraison 1 :** Lundi (08h00 - 13h00) | **Tag Git :** `v1`

---

## 📌 Tâches Communes (08h00 - 08h45)

| Tâche | Responsable | Durée théo. | Fichiers concernés | Routes (CI4) |
| :--- | :---: | :---: | :--- | :--- |
| **Création de la base de données (Tables & Vues)** | Olivier | 0h30 | `base.sql` | N/A |
| **Insertion des données de test (Préfixes, Barèmes, Clients)** | Sanda | 0h15 | `base.sql` | N/A |
| **Configuration CI4 (BaseURL, DB SQLite, Routes)** | Olivier & Sanda | 0h15 | `app/Config/Database.php`, `app/Config/Routes.php`, `.env` | N/A |

### Descriptions par fichier :
* **`base.sql` :** Contient d'une part la rédaction du script SQL de création de toutes les structures de tables nécessaires (`client`, `transaction`, `bareme`, `prefixe`, etc.) ainsi que des vues SQL, et d'autre part les requêtes `INSERT` contenant le jeu d'essai complet (numéros valides, comptes clients avec solde initial, grilles tarifaires par défaut).
* **`app/Config/Database.php` & `.env` :** Fichiers dédiés au paramétrage du projet CodeIgniter 4, incluant la configuration de l'accès à la base de données SQLite et la définition de la variable d'environnement `BaseURL`.
* **`app/Config/Routes.php` :** Fichier de déclaration et d'organisation des routes principales de l'application pour diriger les requêtes vers l'espace client et l'administration.

---

## 🛠️ Côté Opérateur (Olivier) — (08h45 - 11h45)

### 1. Configuration des Préfixes
* **Responsable :** Olivier
* **Durée :** 0h30
* **Descriptions par fichier :**
  * **`app/Models/PrefixeModel.php` :** Modèle gérant les requêtes de base de données pour la liste des préfixes réseau pris en charge par l'opérateur (ex: `033`, `034`, `037`).
  * **`app/Controllers/Admin/PrefixeController.php` :** Contrôleur gérant la logique métier du CRUD (affichage, ajout et suppression des préfixes).
  * **`app/Views/admin/prefixes/index.php` :** Interface utilisateur permettant à l'administrateur d'afficher et de gérer les préfixes.
* **Routes associées :**
  * `GET /admin/prefixes` ➔ `Admin\PrefixeController::index`
  * `POST /admin/prefixes/add` ➔ `Admin\PrefixeController::add`
  * `GET /admin/prefixes/delete/(:num)` ➔ `Admin\PrefixeController::delete/$1`

### 2. Barèmes de Frais par Tranche (Modifiables)
* **Responsable :** Olivier
* **Durée :** 1h00
* **Descriptions par fichier :**
  * **`app/Models/BaremeFraisModel.php` :** Modèle assurant la lecture et l'écriture des tranches de montants et de leurs frais associés dans la base de données.
  * **`app/Controllers/Admin/BaremeController.php` :** Contrôleur traitant l'affichage et la mise à jour dynamique de la grille tarifaire.
  * **`app/Views/admin/baremes/index.php` :** Page d'interface d'administration pour la consultation et l'édition des tarifs par tranche.
* **Routes associées :**
  * `GET /admin/baremes` ➔ `Admin\BaremeController::index`
  * `POST /admin/baremes/update` ➔ `Admin\BaremeController::update`

### 3. Rapports (Situation des Gains & Comptes Clients)
* **Responsable :** Olivier
* **Durée :** 1h30
* **Descriptions par fichier :**
  * **`app/Models/TransactionModel.php` :** Modèle sollicité pour extraire le cumul des frais perçus ainsi que l'état global des comptes clients.
  * **`app/Controllers/Admin/RapportController.php` :** Contrôleur centralisant la logique de calcul du bilan financier et de la synthèse des utilisateurs.
  * **`app/Views/admin/rapports/gains.php` :** Vue du tableau de bord affichant les commissions et gains cumulés par l'opérateur.
  * **`app/Views/admin/rapports/clients.php` :** Vue récapitulative présentant la liste détaillée des comptes clients (soldes et statuts).
* **Routes associées :**
  * `GET /admin/rapports/gains` ➔ `Admin\RapportController::gains`
  * `GET /admin/rapports/clients` ➔ `Admin\RapportController::clients`

> **Sous-total Olivier :** 3h00

---

## 📱 Côté Client (Sanda) — (08h45 - 11h45)

### 1. Login Automatique par Téléphone (Vérification préfixe)
* **Responsable :** Sanda
* **Durée :** 0h45
* **Descriptions par fichier :**
  * **`app/Models/ClientModel.php` :** Modèle gérant la vérification de l'existence d'un numéro et l'insertion à la volée d'un nouveau client.
  * **`app/Controllers/Client/AuthController.php` :** Contrôleur gérant la vérification du préfixe, la création automatique de compte si nécessaire et l'initialisation de la session client.
  * **`app/Views/client/login.php` :** Formulaire minimaliste de saisie du numéro de téléphone pour la connexion/inscription.
* **Routes associées :**
  * `GET /client/login` ➔ `Client\AuthController::login`
  * `POST /client/login` ➔ `Client\AuthController::processLogin`
  * `GET /client/logout` ➔ `Client\AuthController::logout`

### 2. Espace Client (Solde) & Dépôt Automatique
* **Responsable :** Sanda
* **Durée :** 0h45
* **Descriptions par fichier :**
  * **`app/Controllers/Client/DashboardController.php` :** Contrôleur gérant l'accès au tableau de bord et le traitement du formulaire de rechargement/dépôt.
  * **`app/Views/client/dashboard.php` :** Page d'accueil client affichant le solde disponible mis à jour en temps réel.
  * **`app/Views/client/depot.php` :** Formulaire de rechargement permettant de créditer automatiquement le compte.
* **Routes associées :**
  * `GET /client/dashboard` ➔ `Client\DashboardController::index`
  * `GET /client/depot` ➔ `Client\DashboardController::depotForm`
  * `POST /client/depot` ➔ `Client\DashboardController::processDepot`

### 3. Retrait, Transfert (avec calcul frais) & Historique
* **Responsable :** Sanda
* **Durée :** 1h30
* **Descriptions par fichier :**
  * **`app/Models/TransactionModel.php` :** Modèle exécutant l'enregistrement des mouvements (retraits, transferts) et la mise à jour des soldes associés.
  * **`app/Controllers/Client/TransactionController.php` :** Contrôleur gérant les opérations débitrices, le calcul automatique des frais via les barèmes et la validation du solde suffisant.
  * **`app/Views/client/retrait.php` :** Interface de formulaire pour effectuer un retrait d'espèces avec simulation des frais.
  * **`app/Views/client/transfert.php` :** Interface de formulaire pour le transfert d'argent vers un tiers avec calcul de commission.
  * **`app/Views/client/historique.php` :** Page d'affichage du journal récapitulatif complet des transactions du client.
* **Routes associées :**
  * `GET /client/retrait` ➔ `Client\TransactionController::retraitForm`
  * `POST /client/retrait` ➔ `Client\TransactionController::processRetrait`
  * `GET /client/transfert` ➔ `Client\TransactionController::transfertForm`
  * `POST /client/transfert` ➔ `Client\TransactionController::processTransfert`
  * `GET /client/historique` ➔ `Client\TransactionController::historique`

> **Sous-total Sanda :** 3h00

---

## 🧪 Recette, Merge & Tag v1 (11h45 - 13h00)

| Tâche | Responsable | Durée théo. | Fichiers concernés | Routes (CI4) |
| :--- | :---: | :---: | :--- | :--- |
| **Fusion des branches sur `main` & Tests d'intégration** | Olivier & Sanda | 0h45 | Ensemble du projet | Toutes |
| **Nettoyage final BDD (`base.sql`) & Fichier `Taches.md`** | Olivier & Sanda | 0h15 | `base.sql`, `Taches.md` | N/A |
| **Push final & Création du Tag Git (`v1`)** | Sanda | 0h15 | Dépôt Git public | N/A |

### Descriptions par fichier :
* **Ensemble du projet :** Fichiers du dépôt fusionnés sur la branche principale (`main`), avec résolution des conflits Git et exécution des tests de bout en bout.
* **`base.sql` :** Nettoyage et consolidation du script SQL d'exportation pour garantir sa parfaite exécution lors du déploiement.
* **`Taches.md` :** Mise à jour et validation finale du document de suivi du projet.
* **Dépôt Git public :** Publication des commits finaux et application du tag officiel `v1`.

> **Total Chrono :** 5h00 (Livraison finale à 13h00)