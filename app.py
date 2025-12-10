from flask import Flask, request, jsonify
import os
from PIL import Image

app = Flask(__name__)

UPLOAD_FOLDER = "uploads"
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

image_path = None

@app.route("/upload", methods=["POST"])
def upload_image():
    global image_path

    if "image" not in request.files:
        return jsonify({"message": "No image found!"}), 400

    image = request.files["image"]
    image_path = os.path.join(UPLOAD_FOLDER, image.filename)
    image.save(image_path)

    return jsonify({"message": "Image uploaded successfully!"})

@app.route("/preprocess", methods=["GET"])
def preprocess_image():
    global image_path

    if not image_path or not os.path.exists(image_path):
        return jsonify({"status": "error", "message": "No uploaded image found!"})

    img = Image.open(image_path).convert("L")  # grayscale
    img.save(image_path)

    return jsonify({"status": "success", "message": "Preprocessing completed successfully!"})

if __name__ == "__main__":
    app.run(debug=True)
