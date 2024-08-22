<?php

namespace Panos\Biblioteka;

class IsbnDbClient {
    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey, $baseUrl = 'https://api2.isbndb.com/book/') {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    public function getBookByIsbn($isbn)
    {
        $url = $this->baseUrl . $isbn;
        $headers = array(
            "Content-Type: application/json",
            "Authorization: " . $this->apiKey
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error) {
            throw new \Exception("CURL Error: " . $error);
        }

        if ($httpCode != 200) {
            throw new \Exception("HTTP $httpCode returned for $url");
        }

        $fileName = "books/{$isbn}.json";
        file_put_contents($fileName, $response);

        return json_decode($response, true);
    }
}