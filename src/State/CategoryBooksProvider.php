<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Category;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<Category>
 */
class CategoryBooksProvider implements ProviderInterface
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly CategoryRepository $categoryRepository
    ) {}

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        //Récupération de l'ID
        $id = $uriVariables["id"];

        //Récupération de la catégorie
        $category = $this->categoryRepository->find($id);

        //Test si elle n'existe pas
        if (null == $category) {
            throw new NotFoundHttpException("La categorie n'existe pas", code:404);
        }

        //Récupération des livres
        $books = $this->bookRepository->findBooksByCategoryId($id);
        
        //Retourne les lives trouvés
        return $books;
    }
}
