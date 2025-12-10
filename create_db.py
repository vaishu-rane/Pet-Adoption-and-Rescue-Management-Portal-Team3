import sqlite3

DB = "pets.db"

conn = sqlite3.connect(DB)
cursor = conn.cursor()

cursor.execute("DROP TABLE IF EXISTS pets")

cursor.execute("""
CREATE TABLE pets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pet_name TEXT,
    pet_breed TEXT,
    image_path TEXT,
    feature_vector BLOB
)
""")

conn.commit()
conn.close()

print("Database recreated with correct columns!")
