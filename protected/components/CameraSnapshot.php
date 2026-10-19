<?php
/** Snapshot credentials stay in the server environment, never in public URLs. */
class CameraSnapshot
{
    public static function capture($variable)
    {
        $url = pkbEnv($variable, '');
        if ($url === '') {
            throw new CHttpException(503, 'Kamera belum dikonfigurasi.');
        }
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, array('http', 'https'), true)) {
            throw new CHttpException(503, 'Konfigurasi kamera tidak valid.');
        }
        $handle = curl_init($url);
        curl_setopt_array($handle, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_USERPWD => pkbEnv('CAMERA_USERNAME', '') . ':' . pkbEnv('CAMERA_PASSWORD', ''),
        ));
        $image = curl_exec($handle);
        $status = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        if ($image === false || $status !== 200 || @getimagesizefromstring($image) === false) {
            throw new CHttpException(503, 'Gambar kamera tidak tersedia.');
        }
        return base64_encode($image);
    }
}
