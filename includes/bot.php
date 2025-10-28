<?php
// External AI chatbot integration

function gemini_chat($userMessage) {
    if(!defined('AI_API_KEY') || !defined('AI_API_URL')) {
        return [
            'response' => 'API belum dikonfigurasi. Silakan hubungi administrator.',
            'can_answer' => false
        ];
    }

    // Encode parameters untuk URL
    $prompt = urlencode(trim($userMessage));
    $logic = urlencode(LEXA_LOGIC);
    $apikey = urlencode(AI_API_KEY);
    
    // Build URL dengan query parameters
    $url = AI_API_URL . "?prompt={$prompt}&logic={$logic}&apikey={$apikey}";
    
    // Inisialisasi cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Handle error
    if($error || $httpCode !== 200) {
        return [
            'response' => 'Maaf, terjadi kesalahan teknis. Silakan coba lagi atau hubungi notaris langsung untuk bantuan.',
            'can_answer' => false
        ];
    }

    // Decode response
    $result = json_decode($response, true);
    
    // Cek apakah ada message dari API
    if(isset($result['message'])) {
        $aiResponse = trim($result['message']);
        
        // Cek apakah AI menolak menjawab (di luar topik)
        $isOutOfTopic = (
            stripos($aiResponse, 'tidak dapat menjawab') !== false ||
            stripos($aiResponse, 'di luar topik') !== false ||
            stripos($aiResponse, 'tidak bisa membantu') !== false ||
            stripos($aiResponse, 'maaf, saya lexa') !== false
        );
        
        // Cek apakah perlu konsultasi langsung
        $needsNotaris = (
            stripos($aiResponse, 'hubungi notaris') !== false ||
            stripos($aiResponse, 'konsultasi langsung') !== false ||
            stripos($aiResponse, 'sebaiknya berkonsultasi') !== false
        );
        
        return [
            'response' => $aiResponse,
            'can_answer' => !($isOutOfTopic || $needsNotaris)
        ];
    }
    
    // Fallback jika format berbeda
    if(is_string($response) && !empty(trim($response))) {
        $plainText = strip_tags(trim($response));
        return [
            'response' => $plainText,
            'can_answer' => true
        ];
    }

    return [
        'response' => 'Maaf, saya tidak dapat memproses pertanyaan Anda saat ini. Silakan chat dengan notaris profesional untuk bantuan lebih lanjut.',
        'can_answer' => false
    ];
}

function simple_bot_reply($text) {
    // Fallback sederhana jika API tidak tersedia
    $t = strtolower($text);
    if(strpos($t,'biaya') !== false || strpos($t,'harga') !== false) 
        return "Biaya konsultasi berbeda-beda tergantung layanan. Untuk konsultasi 1-on-1 perlu pembayaran (simulasi).";
    if(strpos($t,'dokumen') !== false || strpos($t,'akta') !== false) 
        return "Untuk pembuatan akta tanah biasanya diperlukan identitas, sertifikat, dan dokumen pendukung. Mau diarahkan ke notaris?";
    if(strpos($t,'halo') !== false || strpos($t,'hai') !== false) 
        return "Halo! Saya Lexa, asisten digital Notaris untuk Akta Tanah. Bagaimana saya bisa bantu?";
    if(strlen(trim($text)) < 10) 
        return "Bisa jelaskan lebih detail? Jika pertanyaan terlalu teknis, saya akan arahkan ke notaris.";
    return null;
}

?>
