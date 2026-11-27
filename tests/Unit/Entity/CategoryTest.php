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
        $expect = "test";
        //setter l'attribut
        $testCategory->setName($expect);
        //2 Act
         //Récupérer la valeur du getter
        $actual = $testCategory->getName();
        //3 Assert
        self::assertSame($expect, $actual);
    }

    public function testGetNameReturnBadValue(): void
    {
        //1 Arrange
        //créer un objet vide
        $testCategory = new Category();
        //stocker le résultat attendu
        $expect = "test";
        $value = "test2";
        //setter l'attribut
        $testCategory->setName($value);
        //2 Act
        //Récupérer la valeur du getter
        $actual = $testCategory->getName();
        //3 Assert
        self::assertNotSame($expect, $actual);
    }
    
    //Test qui échoue volontairement
   /*  public function testGetNameReturnInvalid(): void
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
    } */
}
