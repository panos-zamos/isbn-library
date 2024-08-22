<?php

namespace Panos\tests;

use Panos\Biblioteka\App;
use Panos\Biblioteka\Book;
use Panos\Biblioteka\BookRepository;
use Panos\Biblioteka\IsbnDbClient;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    // TODO:
    // - [x] load json list of isbn's
    // - [x] fetch book data
    // - [ ] parse book data to csv

    public function testLoadListOfIsbn()
    {
        $app = new App();
        $list = $app->loadListOfIsbn('input/isbns-test.json');

        $this->assertIsArray($list);
        $this->assertSame(['123-test'], $list);
    }

    public function testFetchBookData()
    {
        $bookRepository = new BookRepository();
        $book = $bookRepository->get('123-test');

        $this->assertIsObject($book);
        $this->assertInstanceOf(Book::class, $book);

        $book = $bookRepository->get('non-existing-isbn');

        $this->assertNull($book);
    }
    public function testFetchSpecificBookData()
    {
        $bookRepository = new BookRepository();
        $book = $bookRepository->get('123-test');
        $expectedBook = new Book(
            'Book 123',
            'Mark Twain',
            '2020',
            'Publisher name'
        );

        $this->assertEquals($expectedBook, $book);
    }

    /**
     * to run this test, first start local server: php -S localhost:8000 tests/fake_isbndb_api.php
     */
    public function testFetchNewBookData()
    {
//        self::markTestSkipped('no fake server');

        $apiKey = 'YOUR_REST_KEY'; // Replace with your actual REST key
        $baseUrl = 'http://localhost:8000/book/';
        $isbnDbClient = new IsbnDbClient($apiKey, $baseUrl);

        $bookRepository = new BookRepository($isbnDbClient);
        $book = $bookRepository->get('NEW-123-test');
        $expectedBook = new Book(
            'Book 123',
            'Mark Twain',
            '2020',
            'Publisher name'
        );

        $this->assertEquals($expectedBook, $book);
    }

//    public function testToCsv()
//    {
//        $app = new App();
//        $repo = new BookRepository();
//        $book = $repo->get('123-test');
//
//
//    }
}
