<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;

class BookTest extends TestCase
{
    public function testGetTitleReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Alice au pays des merveilles";
        //assigner une valeur dans $title
        $book->setTitle($expect);
        //2 Act
        $actual = $book->getTitle();
        //3 Assert
        self::assertSame($expect, $actual);
    }

    public function testGetResumeReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Un livre qui raconte l'histoire d'Alice au pays des merveilles";
        //assigner une valeur dans $resume
        $book->setResume($expect);
        //2 Act
        $actual = $book->getResume();
        //3 Assert
        self::assertSame($expect, $actual);
    }

    public function testGetAuthorReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Lewis Carrol";
        //assigner une valeur dans $author
        $book->setAuthor($expect);
        //2 Act
        $actual = $book->getAuthor();
        //3 Assert
        self::assertSame($expect, $actual);
    }

    public function testGetPublishAtReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = new \DateTimeImmutable();
        //assigner une valeur dans $publishAt
        $book->setPublishAt($expect);
        //2 Act
        $actual = $book->getPublishAt();
        //3 Assert
        self::assertSame($expect, $actual);
    }

    public function testGetTitleNotReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Alice au pays des merveilles";
        //Value set dans $title
        $value = "Le tour du monde en 80 jours";
        //assigner une valeur dans $title
        $book->setTitle($value);
        //2 Act
        $actual = $book->getTitle();
        //3 Assert
        self::assertNotSame($expect, $actual);
    }


    public function testGetResumeNotReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Un livre qui raconte l'histoire du tour du monde en 80 jours";
        //Value set dans $resume
        $value = "Un livre qui raconte l'histoire d'Alice au pays des merveilles";
        //assigner une valeur dans $resume
        $book->setResume($value);
        //2 Act
        $actual = $book->getResume();
        //3 Assert
        self::assertNotSame($expect, $actual);
    }

    public function testGetAuthorNotReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Lewis Carrol";
        //Value set dans $author
        $value = "Victor Hugo";
        //assigner une valeur dans $author
        $book->setAuthor($value);
        //2 Act
        $actual = $book->getAuthor();
        //3 Assert
        self::assertNotSame($expect, $actual);
    }

    public function testGetPublishAtNotReturnExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = new \DateTimeImmutable();
        //Value set dans $publishAt
        $value = new \DateTimeImmutable('2025-01-01');
        //assigner une valeur dans $publishAt
        $book->setPublishAt($value);
        //2 Act
        $actual = $book->getPublishAt();
        //3 Assert
        self::assertNotSame($expect, $actual);
    }
}
