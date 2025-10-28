# Setup AI External API untuk Lexa Chatbot

## Tentang Lexa AI

Lexa adalah chatbot asisten virtual notaris yang **KHUSUS** membantu pengguna dalam konsultasi mengenai **pembuatan dan pengurusan Akta Tanah**.

## Konfigurasi API

### API Endpoint
```
https://api.ferdev.my.id/ai/gptlogic
```

### Parameters:
- `prompt` - Pertanyaan dari user (URL encoded)
- `logic` - System prompt yang mendefinisikan peran Lexa (URL encoded)
- `apikey` - API key untuk akses

### API Key Default:
```
jawaempire
```

## Setup:

### 1. File Konfigurasi Sudah Siap
File `config.php` sudah dikonfigurasi dengan:
- API URL
- API Key
- Logic prompt untuk Lexa

### 2. Tidak Perlu Setup Tambahan
API sudah siap digunakan langsung!

### 3. Test Chatbot
1. Login ke aplikasi
2. Buka menu "Chatbot Konsultasi"
3. Coba tanya: "Apa dokumen yang diperlukan untuk akta jual beli tanah?"

## Fitur Lexa AI Chatbot:

✅ **Berbasis External AI API** - Menggunakan GPT Logic API
✅ **Fokus Akta Tanah** - Hanya menjawab pertanyaan seputar akta tanah
✅ **Auto Redirect** - Jika tidak bisa jawab, arahkan ke chat notaris langsung
✅ **Profesional & Ramah** - Respons yang informatif dan mudah dipahami
✅ **Clear Chat** - Bisa hapus riwayat percakapan

## Topik yang Dijawab Lexa:

Lexa **HANYA** menjawab pertanyaan tentang:
- ✅ Prosedur pembuatan Akta Tanah
- ✅ Dokumen yang diperlukan untuk Akta Tanah
- ✅ Biaya umum atau perkiraan biaya pengurusan
- ✅ Langkah-langkah pengurusan melalui Notaris atau PPAT
- ✅ Waktu yang dibutuhkan dalam proses Akta Tanah
- ✅ Perbedaan jenis akta (jual beli, hibah, waris, dll)

## Topik yang TIDAK Dijawab:

❌ Hukum perdata umum
❌ Urusan pernikahan
❌ Hukum pidana
❌ Ekonomi
❌ Hiburan
❌ Topik di luar akta tanah

**Respons Lexa jika ditanya topik lain:**
> "Maaf, saya Lexa, asisten khusus untuk konsultasi mengenai pembuatan dan pengurusan Akta Tanah. Saya tidak dapat menjawab pertanyaan di luar topik tersebut."

## Contoh Pertanyaan yang Bisa Dijawab:

1. "Apa saja dokumen yang diperlukan untuk membuat akta jual beli tanah?"
2. "Berapa biaya pembuatan akta hibah tanah?"
3. "Bagaimana prosedur balik nama sertifikat tanah?"
4. "Apa perbedaan notaris dan PPAT?"
5. "Berapa lama proses pembuatan akta tanah?"

## Customize Logic:

Jika ingin mengubah perilaku Lexa, edit file `config.php`:

```php
define('LEXA_LOGIC', 'Kamu adalah Lexa...');
```

Anda bisa sesuaikan:
- Gaya bahasa
- Batasan topik
- Format respons
- Dll.

## Troubleshooting:

**Error: "API belum dikonfigurasi"**
→ Pastikan file `config.php` ada dan sudah berisi AI_API_KEY dan AI_API_URL

**Error: "Terjadi kesalahan teknis"**
→ Cek koneksi internet
→ Pastikan API endpoint aktif
→ Cek API key masih valid

**Respons lambat**
→ Normal, API eksternal butuh waktu proses
→ Pastikan koneksi internet stabil

**Lexa menjawab di luar topik**
→ Perbarui LEXA_LOGIC di config.php
→ Perjelas batasan topik dalam logic

## API Response Format:

API mengembalikan JSON:
```json
{
  "result": "Untuk membuat Akta Jual Beli Tanah, Anda memerlukan..."
}
```

Atau plain text response.

## Keamanan:

⚠️ **PENTING**: File `config.php` sudah masuk ke `.gitignore`
- Jangan commit file `config.php` ke Git
- Jangan share API key ke publik
- Gunakan environment variable untuk production

## Biaya:

API ini menggunakan endpoint eksternal. Silakan cek dengan penyedia API untuk:
- Limit request
- Quota
- Biaya (jika ada)

---

**Happy Coding! 🚀**

Lexa siap membantu user Anda dengan konsultasi Akta Tanah! 📄✨

