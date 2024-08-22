<?php

use Panos\Biblioteka\App;

require __DIR__ . '/../vendor/autoload.php';

$apiKey = '55074_16585b8b05f8257429ff5d03a85ef78a'; // Replace with your actual REST key
$baseUrl = 'https://api2.isbndb.com/book/';
$isbnDbClient = new \Panos\Biblioteka\IsbnDbClient($apiKey, $baseUrl);

$bookRepository = new \Panos\Biblioteka\BookRepository($isbnDbClient);
//$isbn = '9780996128100';
//$book = $bookRepository->get('9780996128100');

$timestamp = date('Ymd_His');
$csvFilename = "output/books_{$timestamp}.csv";

$app = new App();
//$list = $app->loadListOfIsbn('input/isbns-test.json');
//$list = $app->loadListOfIsbn('input/isbns.json');
$list = $app->loadListOfIsbn('input/knjige-nemacka.json');
foreach ($list as $isbn) {
    $book = $bookRepository->get($isbn);
    if ($book) {
        echo "$isbn: {$book->title}\n";
        $app->writeBookToCsv($book, $csvFilename);
    } else {
        echo "$isbn: NO DATA!\n";
    }
}
