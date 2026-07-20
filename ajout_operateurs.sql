-- ---------------------------------------------------------
-- Table operateurs : juste une liste de référence (pas de login)
-- ---------------------------------------------------------

CREATE TABLE operateurs (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             VARCHAR(50) NOT NULL UNIQUE,
    commission_pct  NUMERIC(5,2) NOT NULL DEFAULT 0, -- ex: 2.50 pour 2.5%
    actif           INTEGER NOT NULL DEFAULT 1
);

INSERT INTO operateurs (nom, commission_pct) VALUES
('Opérateur A', 2.5),
('Opérateur B', 3.0);

-- ---------------------------------------------------------
-- Rattacher les préfixes externes à un opérateur
-- (les préfixes internes 033/037 restent avec operateur_id = NULL)
-- ---------------------------------------------------------

ALTER TABLE prefixes_operateur ADD COLUMN operateur_id INTEGER REFERENCES operateurs(id) NULL;

INSERT INTO prefixes_operateur (prefixe, actif, operateur_id) VALUES
('032', 1, (SELECT id FROM operateurs WHERE nom = 'Opérateur A')),
('031', 1, (SELECT id FROM operateurs WHERE nom = 'Opérateur B'));

-- ---------------------------------------------------------
-- Étendre operations pour tracer les transferts externes
-- ---------------------------------------------------------

ALTER TABLE operations ADD COLUMN mode_transfert VARCHAR(30) NOT NULL DEFAULT 'interne';
-- valeurs possibles : 'interne', 'externe_intermediaire', 'externe_direct'

ALTER TABLE operations ADD COLUMN operateur_destinataire_id INTEGER REFERENCES operateurs(id) NULL;
ALTER TABLE operations ADD COLUMN numero_destinataire_externe VARCHAR(15) NULL;
ALTER TABLE operations ADD COLUMN commission NUMERIC(12,2) NOT NULL DEFAULT 0;