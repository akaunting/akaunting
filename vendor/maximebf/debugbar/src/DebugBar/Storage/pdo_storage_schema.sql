CREATE TABLE phpdebugbar (
    id TEXT PRIMARY KEY,
    data TEXT,
    meta_utime TEXT,
    meta_datetime TEXT,
    meta_uri TEXT,
    meta_ip TEXT,
    meta_method TEXT
);

CREATE INDEX idx_debugbar_id ON phpdebugbar (id);
CREATE INDEX idx_debugbar_meta_utime ON phpdebugbar (meta_utime);
CREATE INDEX idx_debugbar_meta_datetime ON phpdebugbar (meta_datetime);
CREATE INDEX idx_debugbar_meta_uri ON phpdebugbar (meta_uri);
CREATE INDEX idx_debugbar_meta_ip ON phpdebugbar (meta_ip);
CREATE INDEX idx_debugbar_meta_method ON phpdebugbar (meta_method);
