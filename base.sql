PRAGMA foreign_keys = ON;

CREATE TABLE operateur (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    nom                 TEXT NOT NULL UNIQUE,
    autre_operateur     INTEGER NOT NULL DEFAULT 0,
    pct_commission      REAL NOT NULL DEFAULT 0,
    CHECK (autre_operateur IN (0, 1)),
    CHECK (pct_commission >= 0 AND pct_commission <= 100)
);

CREATE TABLE config (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         TEXT NOT NULL UNIQUE,
    id_operateur    INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateur(id) ON DELETE CASCADE
);

CREATE TABLE type_operation (
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);

CREATE TABLE frais_operation (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation   INTEGER NOT NULL,
    borne_min           REAL NOT NULL,
    borne_max           REAL NOT NULL,
    frais               REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id) ON DELETE CASCADE,
    CHECK (borne_max > borne_min),
    CHECK (frais >= 0)
);

CREATE INDEX idx_frais_type_operation ON frais_operation(id_type_operation);

CREATE TABLE client (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT,
    numero          TEXT NOT NULL UNIQUE,
    solde           REAL NOT NULL DEFAULT 0 CHECK (solde >= 0)
);

CREATE INDEX idx_client_numero ON client(numero);

CREATE TABLE transactions (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    id_client               INTEGER NOT NULL,
    id_type_operation       INTEGER NOT NULL,
    date_heure              TEXT NOT NULL DEFAULT (datetime('now')),
    montant                 REAL NOT NULL CHECK (montant > 0),
    frais_applique          REAL NOT NULL DEFAULT 0 CHECK (frais_applique >= 0),
    commission              REAL NOT NULL DEFAULT 0 CHECK (commission >= 0),
    numero_destinataire     TEXT,
    FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id) ON DELETE RESTRICT
);

CREATE INDEX idx_transactions_client ON transactions(id_client);
CREATE INDEX idx_transactions_type ON transactions(id_type_operation);
CREATE INDEX idx_transactions_date ON transactions(date_heure);

INSERT INTO type_operation (libelle) VALUES ('depot');
INSERT INTO type_operation (libelle) VALUES ('retrait');
INSERT INTO type_operation (libelle) VALUES ('transfert');

INSERT INTO operateur (nom, autre_operateur, pct_commission) VALUES ('Soi-même', 0, 0);