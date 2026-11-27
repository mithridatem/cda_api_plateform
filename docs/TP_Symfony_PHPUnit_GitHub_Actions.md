# Exercices PHPUnit et GitHub Actions

**Public** — CDA Bac +3/4  
**Projet** — [Symfony CDA31-26-01](https://github.com/mithridatem/symfony-cda31-26-01)

Ces exercices reprennent les huit activités du cours. Le premier jour porte sur les tests unitaires locaux. Le deuxième jour automatise la même suite avec GitHub Actions.

## Préparer le travail

Travaillez sur votre fork et une branche de travail. Utilisez **PHP 8.5** et **Composer 2**, comme dans le cours. Depuis la racine du projet, exécutez les commandes suivantes.

```shell
php -v
composer install --no-interaction --prefer-dist --no-scripts
composer check-platform-reqs
php vendor/bin/phpunit --version
```

Les tests seront placés dans `tests/Unit`. Chaque classe de test hérite de `PHPUnit\Framework\TestCase`. Son nom et son fichier se terminent par `Test`. Les méthodes publiques du premier test commencent par `test` et déclarent un retour `void`.

Pour chaque scénario, appliquez les **trois A**.

1. **Arrange** — Préparer les objets, les données et le résultat attendu.
2. **Act** — Appeler le comportement à vérifier.
3. **Assert** — Vérifier le résultat avec une assertion ou les attentes d’un mock.

## Jour 1 — Tests unitaires en local

### Exercice 1 — Compléter les tests de Book

**Objectif** — Vérifier l’ajout et le retrait de catégories avec des objets en mémoire.

Créez `tests/Unit/Entity/BookTest.php`. Commencez par reproduire le test guidé du cours.

```php
<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Book;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

final class BookTest extends TestCase
{
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
}
```

1. Exécutez ce test et vérifiez qu’il passe.
2. Écrivez un deuxième test qui ajoute deux fois **la même instance** de `Category`. Vérifiez qu’elle figure une seule fois dans la collection avec `assertCount()`.
3. Écrivez un troisième test. Préparez un livre contenant deux instances distinctes de `Category`, retirez la première et vérifiez que seule la seconde reste présente. Vous pouvez utiliser `contains()` pour vérifier la présence de chaque instance.
4. Pour observer un échec, attendez temporairement deux catégories dans le test du doublon. Repérez le test, la valeur attendue et la valeur obtenue dans le rapport. Rétablissez ensuite l’assertion correcte.
5. Exécutez le fichier entier, puis sélectionnez une méthode avec `--filter`.

```shell
php vendor/bin/phpunit tests/Unit/Entity/BookTest.php
php vendor/bin/phpunit tests/Unit --filter BookTest
```

**Résultat attendu** — Trois tests réussis. Chaque test prépare ses propres objets et peut être exécuté seul. Votre binôme doit pouvoir identifier les trois A en lisant le code.

### Exercice 2 — Isoler un service avec un stub

**Objectif** — Contrôler les réponses des dépendances d’un service.

Créez `tests/Unit/Service/BookServiceTest.php` dans le namespace `App\Tests\Unit\Service`. Importez `Book`, `BookService`, `BookRepository`, `EntityManagerInterface` et `TestCase` depuis leurs namespaces respectifs.

Dans une méthode de test, utilisez cette préparation pour le premier scénario.

```php
// Arrange
$book = (new Book())->setTitle('Dune');
$repository = $this->createStub(BookRepository::class);
$repository->method('find')->willReturn($book);
$em = $this->createStub(EntityManagerInterface::class);
$service = new BookService($repository, $em);

// Act
$actual = $service->getBookById(42);

// Assert
self::assertSame($book, $actual);
```

1. Vérifiez que `getBookById(42)` retourne la même instance que celle fournie par le stub.
2. Créez un autre test dans lequel `find()` retourne `null`. Vérifiez le résultat de `getBookById(404)` avec `assertNull()`.
3. Créez `CategoryServiceTest.php` dans le même dossier.
4. Configurez un stub de `CategoryRepository` pour que `findAll()` renvoie deux catégories. Vérifiez le résultat de `CategoryService::getCategories()`.
5. Ajoutez un scénario où `findAll()` renvoie un tableau vide.

**Résultat attendu** — Quatre scénarios de lecture réussis entre les deux services. Le stub de lecture maîtrise la réponse du repository. Pour vérifier également l’identifiant transmis au repository, il faudra configurer une attente de mock.

### Exercice 3 — Vérifier une interaction avec un mock

**Objectif** — Vérifier que `BookService::saveBook()` demande la prise en charge du livre et la synchronisation des changements.

Ajoutez cette méthode à `BookServiceTest`. Vérifiez les imports de `App\Entity\Book`, `App\Repository\BookRepository`, `App\Service\BookService` et `Doctrine\ORM\EntityManagerInterface`.

```php
public function testSaveBookPersistsAndFlushes(): void
{
    // Arrange
    $book = new Book();
    $repository = $this->createStub(BookRepository::class);
    $entityManager = $this->createMock(EntityManagerInterface::class);
    $service = new BookService($repository, $entityManager);

    // Déclarer les attentes avant l'appel.
    $entityManager->expects($this->once())
        ->method('persist')
        ->with($this->identicalTo($book));
    $entityManager->expects($this->once())
        ->method('flush');

    // Act
    $service->saveBook($book);
}
```

1. Expliquez le rôle de `createMock()`, `expects()`, `once()`, `method()` et `with()`.
2. Lancez le scénario seul.

```shell
php vendor/bin/phpunit tests/Unit --filter testSaveBookPersistsAndFlushes
```

3. Prédisez le résultat si le service oublie `flush()`.
4. Dans votre copie de travail, commentez temporairement l’appel à `flush()` dans `saveBook()` et relancez le test.
5. Repérez la méthode attendue dans le rapport, rétablissez l’appel et vérifiez que le test repasse.

**Résultat attendu** — Le test détecte l’absence d’appel à `flush()`. Il vérifie aussi un appel à `persist()` avec la même instance de livre. Les attentes portent ici sur les appels et leurs arguments. La vérification de leur ordre et de l’écriture réelle en base demande d’autres tests.

### Exercice 4 — Paramétrer les scénarios de filtrage

**Objectif** — Rejouer un scénario avec plusieurs jeux de données.

`BookService::getBooksByCategoriesName()` découpe une chaîne avec `explode(',', $categories)` puis appelle `BookRepository::findByCategories()`.

1. Commencez par un test simple avec l’entrée `SF,Polar`.
2. Configurez un mock de `BookRepository` qui attend un appel à `findByCategories()` avec `['SF', 'Polar']` et renvoie un tableau contenant un `Book`.
3. Appelez le service et vérifiez son retour.
4. Quand ce scénario passe, remplacez les valeurs fixes par les paramètres `$input` et `$expected` et ajoutez ce fournisseur de données.

Ajoutez l’import suivant en haut du fichier.

```php
use PHPUnit\Framework\Attributes\DataProvider;
```

```php
#[DataProvider('categoryInputs')]
public function testItSplitsCategoryNames(string $input, array $expected): void
{
    // Préparer le mock, appeler le service et vérifier son retour.
}

public static function categoryInputs(): iterable
{
    yield 'une catégorie' => ['SF', ['SF']];
    yield 'deux catégories' => ['SF,Polar', ['SF', 'Polar']];
    yield 'espace conservé' => ['SF, Polar', ['SF', ' Polar']];
}
```

**Résultat attendu** — Trois jeux de données réussis. Expliquez pourquoi le troisième scénario conserve l’espace. Discutez du changement de contrat nécessaire si l’équipe veut supprimer les espaces autour des noms.

### Exercice 5 — Vérifier une exception

**Objectif** — Vérifier une erreur de date et l’absence d’appel au repository.

1. Lisez `BookService::getBooksByAfterPublishAt()`.
2. Préparez un mock de repository et configurez `never()` sur `findPublishedAfter()`.
3. Déclarez `expectException(\DateMalformedStringException::class)` juste avant l’appel testé.
4. Appelez le service avec `date-invalide` et exécutez le test.
5. Expliquez pourquoi le repository ne doit recevoir aucun appel.

**Résultat attendu** — Le test passe lorsque la conversion de la date lève l’exception attendue avant l’accès au repository.

**Pour aller plus loin** — Testez une date valide avec un mock et une contrainte `callback()` qui vérifie la date reçue en la formatant en `Y-m-d`. Décrivez ensuite le test d’intégration nécessaire pour vérifier le filtrage réel en base.

Avant le jour 2, exécutez toute la suite.

```shell
php vendor/bin/phpunit tests/Unit
```

## Jour 2 — GitHub Actions

### Parcours guidé avant l’exercice 6

Construisez progressivement `.github/workflows/phpunit.yml` sur une branche `feat/tests-ci` de votre fork. Faites un commit et un push après chaque étape. Les fichiers de test et `composer.lock` doivent être suivis par Git.

#### Étape 1 — Afficher un message

```yaml
name: Tests unitaires PHP
on: [push]

jobs:
  unit-tests:
    runs-on: ubuntu-latest
    steps:
      - name: Vérifier le déclenchement
        run: echo "Le workflow a démarré"
```

Dans **Actions**, ouvrez l’exécution associée à votre commit, puis le job `unit-tests` et son étape. Retrouvez le message dans le journal. Identifiez dans le fichier le **déclencheur**, le **job**, le **runner** et la **commande**.

#### Étape 2 — Ajouter une seconde étape

Remplacez le bloc `steps` par celui-ci et conservez les autres lignes du fichier.

```yaml
    steps:
      - name: Afficher un premier message
        run: echo "Première étape"
      - name: Afficher un second message
        run: echo "Deuxième étape"
```

Vérifiez les deux messages dans leurs étapes respectives. Expliquez le rôle des tirets et de l’indentation.

#### Étape 3 — Récupérer le code

Remplacez le bloc `steps` par le suivant.

```yaml
    steps:
      - name: Récupérer le dépôt
        uses: actions/checkout@v6
      - name: Vérifier les fichiers récupérés
        run: ls -l composer.json
```

Ajoutez ce bloc au même niveau que `name`, `on` et `jobs`.

```yaml
permissions:
  contents: read
```

Retrouvez `composer.json` dans les logs. Expliquez le rôle de **`uses`**, de **`run`** et de l’action `checkout`. Prédisez ce qui se passerait si vous inversiez les deux étapes.

#### Étape 4 — Préparer PHP et Composer

Ajoutez ces étapes à la suite des précédentes.

```yaml
      - name: Configurer PHP et Composer
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.5'
          tools: composer:v2
          extensions: ctype, iconv, dom, mbstring, tokenizer, xml, xmlwriter
          coverage: none
      - name: Vérifier les versions
        run: |
          php --version
          composer --version
```

Retrouvez les versions dans les logs. Expliquez comment **`with`** transmet les paramètres à l’action et comment le caractère **`|`** permet plusieurs commandes dans une étape.

#### Étape 5 — Installer les dépendances et lancer les tests

```yaml
      - name: Installer les dépendances
        run: composer install --no-interaction --prefer-dist --no-scripts
      - name: Vérifier les prérequis PHP
        run: composer check-platform-reqs
      - name: Exécuter les tests unitaires
        run: php vendor/bin/phpunit tests/Unit
```

Ajoutez ces étapes à la suite. Retrouvez le nombre de tests exécutés et leur résultat. Expliquez pourquoi Composer doit installer `vendor/` avant l’appel à PHPUnit.

### Exercice 6 — Obtenir un premier résultat au push

**Objectif** — Expliquer et vérifier le workflow construit pendant le parcours guidé.

1. Gardez `on: [push]` jusqu’à l’exercice 7.
2. Dans l’historique des exécutions, retrouvez les messages de démonstration, la présence de `composer.json`, les versions PHP et Composer, puis le bilan PHPUnit. Ajoutez une étape manquante si nécessaire et poussez pour la vérifier.
3. Supprimez les étapes de démonstration `echo` et `ls`, puis poussez.
4. Vérifiez que le job conserve la récupération du dépôt, la préparation PHP et Composer, l’installation des dépendances, le contrôle de plateforme et les tests.
5. Comparez le nombre de tests au résultat local. Expliquez pourquoi PHPUnit doit rester dans les dépendances de développement.

**Résultat attendu** — Un job vert sur votre branche, avec des étapes nommées et un résultat PHPUnit identifiable dans le journal.

### Exercice 7 — Déclencher la CI sur une pull request

**Objectif** — Exécuter les tests sur une demande de modification vers `main`.

Vérifiez que votre remote d’écriture vise votre fork. Si Actions y est désactivé, activez les workflows dans l’onglet **Actions** avant de pousser un nouveau commit.

1. Remplacez le bloc `on` existant par celui-ci. Adaptez `main` au nom de votre branche cible si nécessaire.

```yaml
on:
  push:
    branches: [main]
  pull_request:
    branches: [main]
```

2. Poussez, puis ouvrez une PR depuis votre branche vers `main` **dans votre fork**.
3. Dans **Checks**, retrouvez le job et ouvrez son journal. Relevez l’événement et le statut.
4. Expliquez pourquoi `pull_request.branches` regarde la branche de destination.
5. Prédisez le résultat d’un push sur une branche sans PR ouverte, puis d’un commit ajouté à votre PR. Vérifiez le deuxième cas avec un nouveau commit utile à votre travail.

**Résultat attendu** — Une exécution verte sur la PR, puis un nouveau contrôle lorsqu’un commit est ajouté à sa branche source. Avec ce filtre, le push sur une branche de travail sans PR vers `main` ne déclenche aucune exécution.

### Exercice 8 — Provoquer puis corriger une régression

**Objectif** — Diagnostiquer un test en échec depuis les logs de GitHub Actions.

1. Dans votre branche de travail, modifiez temporairement `Book::addCategory()` pour permettre l’ajout en double de la même catégorie.
2. Relancez `BookTest` localement. Relevez l’assertion qui échoue.
3. Poussez un commit d’exercice sur votre PR.
4. Ouvrez le journal du contrôle. Identifiez la première étape rouge, le nom du test et les valeurs attendue et obtenue. Gardez le lien de cette exécution.
5. Rétablissez la condition d’origine dans `Book.php`, relancez la suite et poussez un commit de correction.
6. Gardez le lien de l’exécution verte. Dans la description de la PR, expliquez en deux phrases le problème et la correction.

**Résultat attendu** — Une régression détectée en local et en CI, puis une suite verte après correction.

## Livrables et restitution

- Les tests dans `tests/Unit` et le workflow final `.github/workflows/phpunit.yml`.
- Le lien de votre PR.
- Les liens d’une exécution rouge et d’une exécution verte.
- Une explication de la régression et de sa correction.

Préparez les réponses aux questions suivantes.

1. Quel test détecte le doublon dans `addCategory()` et quelle valeur obtient-il ?
2. Quel rôle jouent le stub de lecture et le mock de sauvegarde ?
3. Quel test supplémentaire permettrait de vérifier la requête Doctrine ?
4. Quelle étape distingue une incompatibilité de plateforme d’une assertion en échec ?

## Documentation

- [PHPUnit — Écriture des tests](https://docs.phpunit.de/en/13.4/writing-tests-for-phpunit.html)
- [PHPUnit — Assertions](https://docs.phpunit.de/en/13.4/assertions.html)
- [PHPUnit — Stubs et mocks](https://docs.phpunit.de/en/13.4/test-doubles.html)
- [GitHub Actions — Concepts](https://docs.github.com/en/actions/get-started/understand-github-actions)
- [GitHub Actions — Syntaxe YAML](https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax)
- [GitHub Actions — Événements](https://docs.github.com/en/actions/reference/workflows-and-actions/events-that-trigger-workflows)
- [Action checkout](https://github.com/actions/checkout)
- [Action setup-php](https://github.com/shivammathur/setup-php)
