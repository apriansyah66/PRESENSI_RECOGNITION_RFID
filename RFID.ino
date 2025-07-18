#include <WiFi.h>
#include <HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#define SS_PIN       21
#define RST_PIN      22
#define BUZZER_PIN   15

const char* ssid = "NAMA_WIFI";              // Ganti dengan SSID WiFi
const char* password = "PASSWORD_WIFI";      // Ganti dengan password WiFi
const char* serverName = "http://192.168.1.10/RFID/simpan_absensi.php"; // Ganti IP sesuai server PHP

MFRC522 rfid(SS_PIN, RST_PIN);
LiquidCrystal_I2C lcd(0x27, 16, 2);

void setup() {
  Serial.begin(115200);
  SPI.begin();
  rfid.PCD_Init();
  pinMode(BUZZER_PIN, OUTPUT);

  Wire.begin(5, 4);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Menghubungkan WiFi");

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi connected");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Tersambung");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan Kartu RFID");

  Serial.println("Sistem Siap. Silakan tempelkan kartu RFID.");
}

String getNamaDariUID(String uid) {
  if (uid == "03:3E:7A:05") return "M. Apriansyah";
  else if (uid == "14:77:7A:05") return "Alam";
  else if (uid == "77:70:7B:05") return "David";
  else return "Tidak dikenal";
}

void beepBuzzer() {
  tone(BUZZER_PIN, 1000, 200);
  delay(200);
  noTone(BUZZER_PIN);
}

void kirimKeServer(String uid) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverName);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "rfid_uid=" + uid + "&id_matkul=1"; // Ganti id_matkul sesuai kebutuhan
    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Respon server: " + response);
    } else {
      Serial.print("Gagal kirim, kode: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi tidak tersambung!");
  }
}

void loop() {
  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) {
    delay(100);
    return;
  }

  String uidString = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) uidString += "0";
    uidString += String(rfid.uid.uidByte[i], HEX);
    if (i < rfid.uid.size - 1) uidString += ":";
  }

  uidString.toUpperCase();

  Serial.println("Kartu Terdeteksi!");
  Serial.print("UID: "); Serial.println(uidString);

  String nama = getNamaDariUID(uidString);
  Serial.print("Nama: "); Serial.println(nama);
  Serial.println("-----------------------");

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Selamat datang,");
  lcd.setCursor(0, 1);
  lcd.print(nama);

  beepBuzzer();
  kirimKeServer(uidString);

  delay(3000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Scan Kartu RFID");

  rfid.PICC_HaltA();
}
