rm data/database.db 
sqlite3 data/database.db < database/create.sql 
sqlite3 data/database.db < database/populate.sql 