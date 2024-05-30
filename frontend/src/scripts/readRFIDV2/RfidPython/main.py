import serial
import requests
import json
import urllib3

# Suppress only the single InsecureRequestWarning from urllib3 needed for development
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# Update this to the correct COM port
serial_port = 'COM8'  # Adjust this to the correct port number
baud_rate = 9600

try:
    arduino = serial.Serial(serial_port, baud_rate, timeout=1)
    print(f"Successfully connected to {serial_port}")
except serial.SerialException as e:
    print(f"Error: Could not open {serial_port}. {e}")
    exit(1)

# Replace with your Symfony server's IP address
server_ip = '127.0.0.1'
server_port = 8000  # Default Symfony port is 8000 for the local server
start = "/attendance/"
finish = "/attending"

# Function to send card UID to Symfony server and get response
def send_to_server(card_uid):
    url = f'http://{server_ip}:{server_port}{start}' + card_uid + f'{finish}'  # Ensure this is HTTP
    print(f"Sending request to URL: {url}")  # Debugging line
    try:
        response = requests.get(url, verify=False)  # Disable SSL verification
        print(f"Server Response: {response.status_code} {response.text}")
        if response.status_code == 200:
            return response.json()  # Parse JSON response
        else:
            print("Error: Non-200 response")
            return None
    except requests.exceptions.RequestException as e:
        print(f"Error sending data to server: {e}")
        return None

while True:
    try:
        line = arduino.readline().decode('latin-1').strip()  # Use 'latin-1' encoding
        if line and line.startswith('SEND:'):
            card_uid = line.split(':')[1]
            card_uid = card_uid[:10]
            print(f"Card UID: {card_uid}")
            server_response = send_to_server(card_uid)
            if server_response:
                # Construct message based on the server response
                message = server_response['status']
                arduino.write((message + '\n').encode('utf-8'))
    except serial.SerialException as e:
        print(f"Serial Exception: {e}")
        break
    except KeyboardInterrupt:
        print("Exiting program.")
        break
