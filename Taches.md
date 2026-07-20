# Taches.md — Suivi des travaux du projet Mobile Money

Binômes : **Olivier** & **Sanda**

> Règle d'organisation : pour limiter les conflits Git, chaque binôme travaille sur des fichiers/dossiers dédiés (voir colonne "Fichiers"). Le fichier `base.sql` est modifié par une seule personne à la fois (voir note en bas).

---

## Livraison 1 — Tag v1

### Côté opérateur (Olivier)

| Tâche | Responsable | Durée théorique | Fichiers concernés |
|---|---|---|---|
| Configuration des préfixes valables de l'opérateur (ex: 033, 037) | Olivier | 1h30 | `operateur/config.*` |
| Création des types d'opérations (dépôt, retrait, transfert) | Olivier | 2h00 | `operateur/operations.*` |
| Barèmes de frais par tranche de montant (modifiable) | Olivier | 2h30 | `operateur/baremes.*` |
| Situation des gains via les différents frais (retrait, transfert) | Olivier | 2h00 | `operateur/rapports/gains.*` |
| Situation des comptes clients (vue opérateur) | Olivier | 1h30 | `operateur/rapports/comptes.*` |

**Sous-total Olivier : ~9h30**

### Côté client (Sanda)

| Tâche | Responsable | Durée théorique | Fichiers concernés |
|---|---|---|---|
| Login automatique avec numéro de téléphone (sans inscription préalable) | Sanda | 2h00 | `client/auth.*` |
| Voir le solde | Sanda | 1h00 | `client/solde.*` |
| Faire un dépôt (automatique) | Sanda | 1h30 | `client/operations/depot.*` |
| Faire un retrait (automatique) | Sanda | 1h30 | `client/operations/retrait.*` |
| Faire un transfert | Sanda | 2h00 | `client/operations/transfert.*` |
| Voir les historiques | Sanda | 1h30 | `client/historique.*` |

**Sous-total Sanda : ~9h30**

### Tâches communes (attention conflits)

| Tâche | Responsable | Durée théorique | Fichiers concernés |
|---|---|---|---|
| Schéma de base de données (tables, vues) | Olivier (rédaction) puis relecture Sanda | 2h00 | `base.sql` |
| Jeux de données de test | Sanda | 1h00 | `base.sql` (section données) |
| Tests d'intégration bout en bout | Olivier & Sanda ensemble | 1h30 | `tests/` |

**Total Livraison 1 (v1) : ~23h30**

---

## Convention pour éviter les conflits GitHub

- Chaque binôme travaille sur sa **propre branche** (`feat/operateur-vX`, `feat/client-vX`) et fait une Pull Request vers `main` avant le tag.
- Le fichier `base.sql` étant partagé, on évite de le modifier en même temps : on se prévient sur le groupe avant toute modification, on `pull` juste avant d'éditer, et on ajoute ses scripts **à la fin** du fichier plutôt que de réécrire les sections existantes.
- `Taches.md` : chacun ajoute ses lignes dans la section de la livraison en cours, sans modifier les lignes déjà écrites par l'autre.
- Tag posé uniquement après merge sur `main` (`v1`, `v2`, `v3` selon la livraison).
