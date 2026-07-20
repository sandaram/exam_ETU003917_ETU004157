-- ---------------------------------------------------------
-- 1. TABLES
-- ---------------------------------------------------------

-- Préfixes valables de l'opérateur (ex: 033, 037)
CREATE TABLE prefixes_operateur (
    id              SERIAL PRIMARY KEY,
    prefixe         VARCHAR(3) NOT NULL UNIQUE,
    actif           BOOLEAN NOT NULL DEFAULT TRUE
);

-- Types d'opérations possibles (dépôt, retrait, transfert)
CREATE TABLE types_operation (
    id              SERIAL PRIMARY KEY,
    code            VARCHAR(20) NOT NULL UNIQUE, -- 'DEPOT', 'RETRAIT', 'TRANSFERT'
    libelle         VARCHAR(50) NOT NULL
);

-- Barème de frais par tranche de montant, modifiable, par type d'opération
CREATE TABLE baremes_frais (
    id                  SERIAL PRIMARY KEY,
    type_operation_id   INTEGER NOT NULL REFERENCES types_operation(id),
    montant_min         NUMERIC(12,2) NOT NULL,
    montant_max         NUMERIC(12,2) NOT NULL,
    frais               NUMERIC(12,2) NOT NULL,
    CHECK (montant_max > montant_min)
);

-- Clients (identifiés par leur numéro de téléphone, login automatique)
CREATE TABLE clients (
    id                  SERIAL PRIMARY KEY,
    numero_telephone    VARCHAR(15) NOT NULL UNIQUE,
    solde               NUMERIC(14,2) NOT NULL DEFAULT 0,
    date_creation       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Historique des opérations (dépôt, retrait, transfert)
CREATE TABLE operations (
    id                  SERIAL PRIMARY KEY,
    client_id           INTEGER NOT NULL REFERENCES clients(id),
    client_destinataire_id INTEGER REFERENCES clients(id), -- utilisé pour les transferts uniquement
    type_operation_id   INTEGER NOT NULL REFERENCES types_operation(id),
    montant             NUMERIC(14,2) NOT NULL,
    frais               NUMERIC(12,2) NOT NULL DEFAULT 0,
    solde_apres         NUMERIC(14,2) NOT NULL,
    date_operation       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- 2. VUES
-- ---------------------------------------------------------

-- Situation des gains de l'opérateur via les frais (retrait et transfert)
CREATE VIEW vue_situation_gains AS
SELECT
    t.code                  AS type_operation,
    COUNT(o.id)              AS nombre_operations,
    SUM(o.frais)             AS total_frais_percus
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
WHERE t.code IN ('RETRAIT', 'TRANSFERT')
GROUP BY t.code;

-- Situation des comptes clients (vue opérateur)
CREATE VIEW vue_situation_comptes_clients AS
SELECT
    c.id,
    c.numero_telephone,
    c.solde,
    c.date_creation,
    COUNT(o.id)              AS nombre_operations
FROM clients c
LEFT JOIN operations o ON o.client_id = c.id
GROUP BY c.id, c.numero_telephone, c.solde, c.date_creation;

-- Historique des opérations d'un client (utilisée côté client)
CREATE VIEW vue_historique_client AS
SELECT
    o.id,
    o.client_id,
    t.code                  AS type_operation,
    o.montant,
    o.frais,
    o.solde_apres,
    o.date_operation
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
ORDER BY o.date_operation DESC;

-- ---------------------------------------------------------
-- 3. DONNEES DE TEST
-- ---------------------------------------------------------

-- Préfixes valables
INSERT INTO prefixes_operateur (prefixe) VALUES
('033'),
('037');

-- Types d'opérations
INSERT INTO types_operation (code, libelle) VALUES
('DEPOT', 'Dépôt'),
('RETRAIT', 'Retrait'),
('TRANSFERT', 'Transfert');

-- Barème de frais (exemple donné dans le sujet)
-- Appliqué ici au RETRAIT et au TRANSFERT (le DEPOT est gratuit)
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais)
SELECT id, v.montant_min, v.montant_max, v.frais
FROM types_operation t
CROSS JOIN (VALUES
    (100,        1000,       50),
    (1001,       5000,       50),
    (5001,       10000,      100),
    (10001,      25000,      200),
    (25001,      50000,      400),
    (50001,      100000,     800),
    (100001,     250000,     1500),
    (250001,     500000,     1500),
    (500001,     1000000,    2500),
    (1000001,    2000000,    3000)
) AS v(montant_min, montant_max, frais)
WHERE t.code IN ('RETRAIT', 'TRANSFERT');

-- Quelques clients de test
INSERT INTO clients (numero_telephone, solde) VALUES
('0331234567', 50000),
('0372345678', 120000),
('0339876543', 0);

-- Quelques opérations de test
INSERT INTO operations (client_id, client_destinataire_id, type_operation_id, montant, frais, solde_apres)
VALUES
(1, NULL, (SELECT id FROM types_operation WHERE code = 'DEPOT'), 50000, 0, 50000),
(2, NULL, (SELECT id FROM types_operation WHERE code = 'DEPOT'), 150000, 0, 150000),
(2, NULL, (SELECT id FROM types_operation WHERE code = 'RETRAIT'), 30000, 400, 119600),
(1, 2,    (SELECT id FROM types_operation WHERE code = 'TRANSFERT'), 20000, 200, 29800);

