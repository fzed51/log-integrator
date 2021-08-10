CREATE TABLE li_logs (
    id_logs INTEGER PRIMARY KEY AUTOINCREMENT,
    l_reflog TEXT NOT NULL UNIQUE,
    l_time TEXT NOT NULL,
    l_level TEXT NOT NULL,
    l_refreq TEXT ,
    l_logdata TEXT NOT NULL,
    l_create_at TEXT
);
---
CREATE INDEX ix_logs_time ON li_logs(l_time);
---
CREATE INDEX ix_logs_level ON li_logs(l_level);

