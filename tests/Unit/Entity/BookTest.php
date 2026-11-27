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

    public function testSetTitleAssignExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Alice au pays des merveilles";
        //2 Act
        $book->setTitle($expect);
        //3 Assert
        self::assertSame($expect, $book->getTitle());
    }

    public function testSetResumeAssignExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Un livre qui raconte l'histoire d'Alice au pays des merveilles";
        //2 Act
        $book->setResume($expect);
        //3 Assert
        self::assertSame($expect, $book->getResume());
    }

    public function testSetAuthorAssignExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Lewis Carrol";
        //2 Act
        $book->setAuthor($expect);
        //3 Assert
        self::assertSame($expect, $book->getAuthor());
    }

    public function testSetPublishAtAssignExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = new \DateTimeImmutable();
        //2 Act
        $book->setPublishAt($expect);
        //3 Assert
        self::assertSame($expect, $book->getPublishAt());
    }

    public function testSetTitleAssignNotExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Alice au pays des merveilles";
        $actual = "Un livre qui raconte l'histoire d'Alice au pays des merveilles";
        //2 Act
        $book->setTitle($actual);
        //3 Assert
        self::assertNotSame($expect, $book->getTitle());
    }

    public function testSetresumeAssignNotExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Un livre qui raconte l'histoire du tour du monde en 80 jours";
        //Value set dans $resume
        $actual = "Un livre qui raconte l'histoire d'Alice au pays des merveilles";
        //2 Act
        $book->setResume($actual);
        //3 Assert
        self::assertNotSame($expect, $book->getResume());
    }

    public function testSetAuthorAssignNotExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = "Lewis Carrol";
        //Value set dans $author
        $actual = "Victor Hugo";
        //2 Act
        $book->setAuthor($actual);
        //3 Assert
        self::assertNotSame($expect, $book->getAuthor());
    }

    public function testSetPublishAtAssignNotExpectedValue(): void
    {
        //1 Arrange
        $book = new Book();
        $expect = new \DateTimeImmutable();
        //Value set dans $author
        $actual = new \DateTimeImmutable('2026-11-26');
        //2 Act
        $book->setPublishAt($actual);
        //3 Assert
        self::assertNotSame($expect, $book->getPublishAt());
    }

    public function testSetTitleExpectedErrorException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\TypeError::class);
        //3 Act
        $book->setTitle(["titre"]);
    }

    public function testSetResumeExpectedErrorException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\TypeError::class);
        //3 Act
        $book->setResume(["Résumé du livre"]);
    }

    public function testSetAuthorExpectedErrorException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\TypeError::class);
        //3 Act
        $book->setAuthor(new \DateTime());
    }

    public function testSetPublishAtExpectedErrorException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\TypeError::class);
        //3 Act
        $book->setPublishAt("2026-01-01");
    }

    public function testSetTitleExpectedInvalidArgumentException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\ArgumentCountError::class);
        //3 Act
        $book->setTitle();
    }

    public function testSetResumeExpectedInvalidArgumentException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\ArgumentCountError::class);
        //3 Act
        $book->setResume();
    }

    public function testSetAuthorExpectedInvalidArgumentException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\ArgumentCountError::class);
        //3 Act
        $book->setAuthor();
    }

    public function testSetPublishAtExpectedInvalidArgumentException(): void
    {
        //1 Arrange
        $book = new Book();
        //2 Expect
        $this->expectException(\ArgumentCountError::class);
        //3 Act
        $book->setAuthor();
    }

/*     public function testSetTitleExpectedRisky(): void
    {
        //1 Arrange
        $book = new Book();
   
        //3 Act
        $book->setTitle("titre du livre", "test");
    }

    public function testSetResumeExpectedRisky(): void
    {
        //1 Arrange
        $book = new Book();

        //3 Act
        $book->setResume("Résumé du livre", "test");
    }

    public function testSetAuthorExpectedRisky(): void
    {
        //1 Arrange
        $book = new Book();
     
        //3 Act
        $book->setAuthor("auteur du livre", "test");
    }

    public function testSetPublishAtExpectedRisky(): void
    {
        //1 Arrange
        $book = new Book();

        //3 Act
        $book->setPublishAt(new \DateTimeImmutable(), "test");
    }  */

    public function testAddCategoryStoresTheCategoryInstance(): void
    {
        // Arrange
        $book = new Book();
        $category = new Category();
        $expected = [$category];

        // Act
        $book->addCategory($category);

        // Assert
        $actual = $book->getCategories()->toArray();
        self::assertSame($expected, $actual);
    }

     public function testAddTheSameCategoryStoresTowtimesCategoryInstance(): void
    {
        // Arrange
        $book = new Book();
        $category = new Category();

        // Act
        $book->addCategory($category);
        $book->addCategory($category);

        // Assert
        self::assertCount(1,$book->getCategories());
    }

     public function testAddDisctinctCategoryStores(): void
    {
        // Arrange
        $book = new Book();
        $cat1 = new Category();
        $cat2 = new Category();

        // Act
        $book->addCategory($cat1);
        //Ajout d'un doublon
        $book->addCategory($cat1);
        $book->addCategory($cat2);
        $book->removeCategory($cat1);

       
        // Assert
        self::assertSame(true, $book->getCategories()->contains($cat2));
        self::assertCount(1,$book->getCategories());
    }
}
