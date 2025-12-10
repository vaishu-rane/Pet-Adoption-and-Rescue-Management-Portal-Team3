import sqlite3
import numpy as np
import pickle
import sys
from tensorflow.keras.preprocessing import image
from tensorflow.keras.applications.efficientnet import preprocess_input
from tensorflow.keras.applications import EfficientNetB0

DB = "pets.db"

model = EfficientNetB0(weights="imagenet", include_top=False, pooling="avg")

def extract_vector(img_path):
    img = image.load_img(img_path, target_size=(224,224))
    arr = image.img_to_array(img)
    arr = np.expand_dims(arr, 0)
    arr = preprocess_input(arr)
    return model.predict(arr).flatten()

def cosine_similarity(a, b):
    return np.dot(a, b) / (np.linalg.norm(a) * np.linalg.norm(b))

uploaded_img = sys.argv[1]
query_vec = extract_vector(uploaded_img)

conn = sqlite3.connect(DB)
cursor = conn.cursor()

cursor.execute("SELECT pet_name, pet_breed, image_path, feature_vector FROM pets")
rows = cursor.fetchall()

best_match = None
best_score = -1

for name, breed, img_path, fv in rows:
    db_vec = pickle.loads(fv)
    score = cosine_similarity(query_vec, db_vec)
    if score > best_score:
        best_score = score
        best_match = (name, breed, img_path)

conn.close()

print(best_match[0])
print(best_match[1])
print(best_match[2])
print(best_score)
