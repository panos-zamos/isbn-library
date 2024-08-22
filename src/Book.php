<?php

namespace Panos\Biblioteka;

class Book
{
    public function __construct(
        readonly public string $title,
        readonly public string $author,
        readonly public string $published,
        readonly public string $publisher,
    )
    {
    }

}