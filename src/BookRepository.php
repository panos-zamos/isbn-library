<?php

namespace Panos\Biblioteka;

class BookRepository
{

    public function __construct(readonly ?IsbnDbClient $isbnDbClient = null)
    {
    }

    public function get(string $isbn): ?Book
    {
        $book = $this->getFromCache($isbn);

        if ($book) {
            return $book;
        }

        $book = $this->getFromApi($isbn);
        // TODO save to cache?

        return $book;
    }

    private function getFromCache($isbn): ?Book
    {
        $filePath = 'books/' . $isbn . '.json'; // Construct the file name from the ISBN

        if (file_exists($filePath)) {
            $jsonData = file_get_contents($filePath); // Read the file contents
            $data = json_decode($jsonData, true); // Decode the JSON into an associative array
            return $this->getBookFromJson($data);
        }

        return null; // Return null if the file does not exist or if there is an error
    }

    private function getFromApi(string $isbn): ?Book
    {
        if (!isset($this->isbnDbClient)) {
            return null;
        }

        try {
            $data = $this->isbnDbClient->getBookByIsbn($isbn);
            return $this->getBookFromJson($data);

        } catch (\Exception $e) {
//            echo "Error: " . $e->getMessage();
        }

        return null;
    }

    /**
     * @param mixed $data
     * @param Book $book
     * @return Book
     */
    private function getBookFromJson(mixed $data): ?Book
    {
        if ($data && isset($data['book'])) {
            $bookData = $data['book'];
            // Extract the necessary information to create a Book object
            $title = $bookData['title'] ?? '--no-title--';
            $authors = $bookData['authors'][0] ?? '--no-author--'; // Assuming the first author is the main one
            $datePublished = isset($bookData['date_published']) ? substr($bookData['date_published'], 0, 4) : '--no-published--'; // Extracting the year
            $publisher = $bookData['publisher'] ?? '--no-publisher--';

            // Create a new Book object with the extracted data
            $book = new Book(
                $title,
                $authors,
                $datePublished,
                $publisher
            );

            return $book;
        }

        return null;
    }
}