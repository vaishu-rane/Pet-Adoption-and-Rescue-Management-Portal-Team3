import sqlite3, pickle

DB = "pets.db"
FEATURES_FILE = "model/features.pkl"

# Load features.pkl
features = pickle.load(open(FEATURES_FILE, "rb"))

conn = sqlite3.connect(DB)
cursor = conn.cursor()

for path, vector in features.items():
    # Extract pet name from folder: dataset/dog/dog1.jpg   →   dog
    pet_breed = path.split("\\")[-2]  # dog or cat

    # Extract image name
    pet_name = path.split("\\")[-1]

    # Convert vector to bytes
    vector_bytes = pickle.dumps(vector)

    cursor.execute("""
        INSERT INTO pets (pet_name, pet_breed, image_path, feature_vector)
        VALUES (?, ?, ?, ?)
    """, (pet_name, pet_breed, path, vector_bytes))

conn.commit()
conn.close()

print("All dataset images inserted successfully!")
