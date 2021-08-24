CREATE TABLE li_integration (
                         id_integration INTEGER PRIMARY KEY AUTOINCREMENT,
                         i_type TEXT NOT NULL,
                         l_directory TEXT,
                         l_pattern TEXT,
                         l_update_at TEXT,
                         l_create_at TEXT
);