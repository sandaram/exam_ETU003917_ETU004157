# Répartition des Tâches - Projet Mobile Money (S4 Info & Design)
**Créneau Livraison 1 :** Lundi (08h00 - 13h00) | **Tag Git :** `v1`

---

## 📌 Tâches Communes (08h00 - 08h45)

| Tâche | Responsable | Durée théo. | Fichiers concernés |
| :--- | :---: | :---: | :--- |
| **Création de la base de données (Tables & Vues)** | Olivier | 0h30 | `base.sql` |
| **Insertion des données de test (Préfixes, Barèmes, Clients)** | Sanda | 0h15 | `base.sql` |
| **Configuration CI4 (BaseURL, DB SQLite, Routes)** | Olivier & Sanda | 0h15 | `app/Config/Database.php`, `app/Config/Routes.php`, `.env` |

---

## 🛠️ Côté Opérateur (Olivier) — (08h45 - 11h45)

### 1. Configuration des Préfixes
* **Responsable :** Olivier
* **Durée :** 0h30
* **Fichiers concernés :**
  * `app/Models/PrefixeModel.php`
  * `app/Controllers/Admin/PrefixeController.php`
  * `app/Views/admin/prefixes/index.php`

### 2. Barèmes de Frais par Tranche (Modifiables)
* **Responsable :** Olivier
* **Durée :** 1h00
* **Fichiers concernés :**
  * `app/Models/BaremeFraisModel.php`
  * `app/Controllers/Admin/BaremeController.php`
  * `app/Views/admin/baremes/index.php`

### 3. Rapports (Situation des Gains & Comptes Clients)
* **Responsable :** Olivier
* **Durée :** 1h30
* **Fichiers concernés :**
  * `app/Models/TransactionModel.php`
  * `app/Controllers/Admin/RapportController.php`
  * `app/Views/admin/rapports/gains.php`
  * `app/Views/admin/rapports/clients.php`

> **Sous-total Olivier :** 3h00

---

## 📱 Côté Client (Sanda) — (08h45 - 11h45)

### 1. Login Automatique par Téléphone (Vérification préfixe)
* **Responsable :** Sanda
* **Durée :** 0h45
* **Fichiers concernés :**
  * `app/Models/ClientModel.php`
  * `app/Controllers/Client/AuthController.php`
  * `app/Views/client/login.php`

### 2. Espace Client (Solde) & Dépôt Automatique
* **Responsable :** Sanda
* **Durée :** 0h45
* **Fichiers concernés :**
  * `app/Controllers/Client/DashboardController.php`
  * `app/Views/client/dashboard.php`
  * `app/Views/client/depot.php`

### 3. Retrait, Transfert (avec calcul frais) & Historique
* **Responsable :** Sanda
* **Durée :** 1h30
* **Fichiers concernés :**
  * `app/Models/TransactionModel.php`
  * `app/Controllers/Client/TransactionController.php`
  * `app/Views/client/retrait.php`
  * `app/Views/client/transfert.php`
  * `app/Views/client/historique.php`

> **Sous-total Sanda :** 3h00

---

## 🧪 Recette, Merge & Tag v1 (11h45 - 13h00)

| Tâche | Responsable | Durée théo. | Fichiers concernés |
| :--- | :---: | :---: | :--- |
| **Fusion des branches sur `main` & Tests d'intégration** | Olivier & Sanda | 0h45 | Ensemble du projet |
| **Nettoyage final BDD (`base.sql`) & Fichier `Taches.md`** | Olivier & Sanda | 0h15 | `base.sql`, `Taches.md` |
| **Push final & Création du Tag Git (`v1`)** | Sanda | 0h15 | Dépôt Git public |

> **Total Chrono :** 5h00 (Livraison finale à 13h00)