from flask import Flask, request, jsonify

app = Flask(__name__)

# Mock predictor endpoint
@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json() or {}
    try:
        ph = float(data.get('ph', 7))
        area = float(data.get('area', 1))
    except (TypeError, ValueError):
        return jsonify({'error': 'Invalid input. Provide numeric "ph" and "area".'}), 400

    # Formula: rendimiento = 5 * (1 + (7 - ph)/14) * area
    rendimiento = 5 * (1 + (7 - ph) / 14.0) * area

    return jsonify({'ok': True, 'rendimiento': rendimiento})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
