<?php
namespace Panos\Biblioteka;

class App
{
    public function loadListOfIsbn(string $listFilename): array
    {
        // Attempt to read the contents of the JSON file
        $jsonContents = file_get_contents($listFilename);
        if ($jsonContents === false) {
            // Handle error if file cannot be read
            throw new \Exception("Unable to read file: {$listFilename}");
        }

        // Decode the JSON contents into an associative array
        $jsonData = json_decode($jsonContents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Handle error if JSON cannot be decoded
            throw new \Exception("Invalid JSON in file: {$listFilename}");
        }

        // Initialize an array to hold the ISBNs
        $isbnList = [];

        // Check if the decoded JSON is an array of objects
        if (is_array($jsonData)) {
            // Extract the ISBN property from each JSON object
            foreach ($jsonData as $jsonObject) {
                if (is_array($jsonObject) && isset($jsonObject['ISBN'])) {
                    $isbnList[] = $jsonObject['ISBN'];
                }
            }
        } else {
            // Handle error if JSON is not an array of objects
            throw new \Exception("Expected an array of JSON objects in file: {$listFilename}");
        }

        // Return the list of ISBNs
        return $isbnList;
    }

    public function writeBookToCsv(Book $book, $csvFilename)
    {
        $sequenceNumber = 1; // Initialize sequence number

        if (!file_exists($csvFilename)) {
            // Create the CSV file and add the header if it doesn't exist
            $header = ["Sequence Number", "Author Name", "Title", "Place of Publishing", "Year of Publishing", "Publisher Name"];
            $fp = fopen($csvFilename, 'w');
            fputcsv($fp, $header);
        } else {
            // Open the CSV file to append data
            $fp = fopen($csvFilename, 'a');
            // Get the last sequence number
            $lastLine = `tail -n 1 $csvFilename`;
            $lastData = str_getcsv($lastLine);
            $sequenceNumber = intval($lastData[0]) + 1;
        }

        // Extract book data
        $authorName = $book->author;
        $title = $book->title;
        $placeOfPublishing = '--';
        $yearOfPublishing = $book->published;
        $publisherName = $book->publisher;

        // Append book data to CSV
        $row = [$sequenceNumber, $authorName, $title, $placeOfPublishing, $yearOfPublishing, $publisherName];
        fputcsv($fp, $row);
        fclose($fp);
    }
}