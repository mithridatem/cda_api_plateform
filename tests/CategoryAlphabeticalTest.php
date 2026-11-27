<?php

namespace App\Tests;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RequestStack;

final class CategoryAlphabeticalTest extends WebTestCase
{
    public function testCategoryNameIsReadFromThePath(): void
    {
        $client = self::createClient();
        $repository = $this->createMock(CategoryRepository::class);
        $repository->expects(self::once())->method('findBy')
            ->with(['name' => 'Science fiction'])
            ->willReturn([$this->category(1, 'Science fiction')]);
        self::getContainer()->set(CategoryRepository::class, $repository);

        $client->request('GET', '/api/cat/Science%20fiction');

        self::assertResponseIsSuccessful();
        $categories = json_decode($client->getResponse()->getContent(), true, flags: JSON_THROW_ON_ERROR);
        self::assertSame(['Science fiction'], array_column($categories, 'name'));
    }

    public function testAlphabeticalRouteReturnsTheFullList(): void
    {
        $client = self::createClient();
        $requestStack = self::getContainer()->get(RequestStack::class);
        $repository = $this->createMock(CategoryRepository::class);
        $repository
            ->expects(self::once())
            ->method('findAllCategoryOrderAlphabetical')
            ->willReturnCallback(function () use ($requestStack): array {
                $operation = $requestStack
                    ->getCurrentRequest()
                    ->attributes->get('_api_operation');

                self::assertSame('desc', $operation->getParameters()->get('order')->getValue());

                return [$this->category(1, 'Fantasy'), $this->category(2, 'Histoire')];
            });

        self::getContainer()->set(CategoryRepository::class, $repository);

        $client->request('GET', '/api/categories/alphabetical?order=desc');

        self::assertResponseIsSuccessful();
        $categories = json_decode(
            $client
                ->getResponse()
                ->getContent(),
            true,
            flags: JSON_THROW_ON_ERROR
        );
        self::assertSame(['Fantasy', 'Histoire'], array_column($categories, 'name'));
    }

    private function category(int $id, string $name): Category
    {
        $category = (new Category())->setName($name);
        (new \ReflectionProperty(Category::class, 'id'))->setValue($category, $id);

        return $category;
    }
}
