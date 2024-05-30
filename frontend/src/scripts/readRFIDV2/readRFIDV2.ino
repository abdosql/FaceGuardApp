#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <Bonezegei_LCD1602_I2C.h>

#define SS_PIN 10
#define RST_PIN 9
MFRC522 mfrc522(SS_PIN, RST_PIN);
Bonezegei_LCD1602_I2C lcd(0x27);

int validRFIDCount = 0;

void setup() {
    Serial.begin(9600);
    SPI.begin();
    mfrc522.PCD_Init();

    lcd.begin();
    lcd.setPosition(1, 0);
    lcd.print("Ready.");

    Serial.println("Place your card near the reader...");
}

void loop() {
    // Check if a new card is present and can be read
    if (!mfrc522.PICC_IsNewCardPresent() || !mfrc522.PICC_ReadCardSerial()) {
        return;
    }

    // Read the name from the card
    String name = readRfidData();
    Serial.println(name);
    if (name.length() == 0) {
        Serial.println("Read failed or invalid card.");
        lcd.clear();
        lcd.setPosition(0, 0);
        lcd.print("Invalid Card. ): ");
    } else {
        Serial.println("SEND:" + name); // Prefix with "SEND:" for the Python script

        // Wait for the server response
        String response = Serial.readStringUntil('\n');
        delay(500); // Reduced delay to 500ms for faster feedback

        // Update the LCD display based on server response
        lcd.clear();
        if (response == "success") {
            validRFIDCount++;
            String countStr = String(validRFIDCount);
            const char* countChar = countStr.c_str();
            Serial.println("Valid Card Detected");
            lcd.setPosition(0, 0);
            lcd.print("Student Count: ");
            lcd.print(countChar);
        } else if(response == "null"){
            Serial.println("Invalid Card Detected");
            lcd.setPosition(0, 0);
            lcd.print("Doesn't exist");
        }else{
            lcd.setPosition(0, 0);
            lcd.print("Declined");
        }
    }

    // Halt PICC to let it be detected again
    mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();
}

String readRfidData() {
    MFRC522::MIFARE_Key key;
    for (byte i = 0; i < 6; i++) key.keyByte[i] = 0xFF;

    byte buffer1[18];
    byte bufferSize1 = sizeof(buffer1);
    byte block1 = 4;

    byte buffer2[18];
    byte bufferSize2 = sizeof(buffer2);
    byte block2 = 1;

    MFRC522::StatusCode status;

    // Authenticate using key A on sector 1 for the first block
    status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, block1, &key, &(mfrc522.uid));
    if (status != MFRC522::STATUS_OK) {
        Serial.print(F("Authentication failed for block 4: "));
        Serial.println(mfrc522.GetStatusCodeName(status));
        return "";
    }

    // Read data from block 4 (First Name)
    status = mfrc522.MIFARE_Read(block1, buffer1, &bufferSize1);
    if (status != MFRC522::STATUS_OK) {
        Serial.print(F("Read failed for block 4: "));
        Serial.println(mfrc522.GetStatusCodeName(status));
        return "";
    }

    // Authenticate using key A on sector 1 for the second block
    status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, block2, &key, &(mfrc522.uid));
    if (status != MFRC522::STATUS_OK) {
        Serial.print(F("Authentication failed for block 1: "));
        Serial.println(mfrc522.GetStatusCodeName(status));
        return "";
    }

    // Read data from block 1 (Last Name)
    status = mfrc522.MIFARE_Read(block2, buffer2, &bufferSize2);
    if (status != MFRC522::STATUS_OK) {
        Serial.print(F("Read failed for block 1: "));
        Serial.println(mfrc522.GetStatusCodeName(status));
        return "";
    }

    // Concatenate the first and last names and return
    String fullName = "";
    for (byte i = 0; i < bufferSize1; i++) {
        if (buffer1[i] != 32) { // Ignore spaces
            fullName += (char)buffer1[i];
        }
    }
    fullName += " "; // Add space between first and last names
    for (byte i = 0; i < bufferSize2; i++) {
        if (buffer2[i] != 32) { // Ignore spaces
            fullName += (char)buffer2[i];
        }
    }

    return fullName;
}
