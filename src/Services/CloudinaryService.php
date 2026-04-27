<?php
declare(strict_types=1);
namespace App\Services;

class CloudinaryService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
        $this->apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
        $this->apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
    }

    public function upload(string $filePath, string $folder = 'profiles'): ?string
    {
        if (empty($this->cloudName) || empty($this->apiKey) || str_contains($this->apiKey, 'dummy')) {
            // If dummy or missing, return a local path or placeholder for now
            return null; 
        }

        $timestamp = time();
        $params = [
            'folder' => $folder,
            'timestamp' => $timestamp,
        ];
        
        ksort($params);
        $signatureStr = "";
        foreach ($params as $key => $value) {
            $signatureStr .= "$key=$value&";
        }
        $signatureStr = rtrim($signatureStr, '&') . $this->apiSecret;
        $signature = sha1($signatureStr);

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";
        
        $postData = array_merge($params, [
            'api_key' => $this->apiKey,
            'signature' => $signature,
            'file' => new \CURLFile($filePath)
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        curl_close($ch);

        return $data['secure_url'] ?? null;
    }
}
