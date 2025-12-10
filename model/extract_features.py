import os
import pickle
import numpy as np
from tensorflow.keras.preprocessing import image
from tensorflow.keras.applications.efficientnet import preprocess_input
from tensorflow.keras.applications import EfficientNetB0

DATASET_DIR = "petdataset"         # <-- Correct path
OUTPUT = "model/features.pkl"    # <-- Saves inside model folder

print("Loading EfficientNetB0 model...")
model = EfficientNetB0(weights="imagenet", include_top=False, pooling="avg")

def get_vector(img_path):
    img = image.load_img(img_path, target_size=(224,224))
    arr = image.img_to_array(img)
    arr = np.expand_dims(arr, 0)
    arr = preprocess_input(arr)
    return model.predict(arr).flatten()

features = {}

print("Starting feature extraction...\n")
for folder in os.listdir(DATASET_DIR):
    fpath = os.path.join(DATASET_DIR, folder)
    if not os.path.isdir(fpath):
        continue

    print("\nFolder:", folder)

    for img in os.listdir(fpath):
        path = os.path.join(fpath, img)
        print("Extracting:", path)
        features[path] = get_vector(path)

pickle.dump(features, open(OUTPUT, "wb"))
print("\nDONE! Saved to:", OUTPUT)
