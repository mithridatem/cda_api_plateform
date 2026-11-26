<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Category;

class CategoryTest extends TestCase
{
    public function testGetNameReturnGoodValue(): void
    {
        //1 Arrange
        //créer un objet vide
        $testCategory = new Category();
        //stocker le résultat attendu
        $value = "test";
        //setter l'attribut
        $testCategory->setName($value);
        //2 Act
        $result = $testCategory->getName();
        //3 Assert
        self::assertSame($value, $result);
    }

    public function testGetNameReturnBadValue(): void
    {
        //1 Arrange
        //créer un objet vide
        $testCategory = new Category();
        //stocker le résultat attendu
        $result = "test";
        $value = "test2";
        //setter l'attribut
        $resultat = $testCategory->setName($value);
        //2 Act
        //3 Assert
        self::assertNotSame($resultat, $result);
    }
    
    //Test qui échoue volontairement
    public function testGetNameReturnInvalid(): void
    {
        //1 Arrange
        //créer un objet vide
        $testCategory = new Category();
        //stocker le résultat attendu
        $result = "test";
        $value = "test2";
        //setter l'attribut
        $resultat = $testCategory->setName($value);
        //2 Act
        //3 Assert
        self::assertNotSame($result, $result);
    }
}
