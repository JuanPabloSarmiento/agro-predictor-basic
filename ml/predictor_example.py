import requests

# Example usage of the mock ML service
if __name__ == '__main__':
    url = 'http://localhost:5001/predict'
    payload = {'ph': 6.5, 'area': 2.0}
    r = requests.post(url, json=payload)
    print('Status:', r.status_code)
    print('Response:', r.json())
