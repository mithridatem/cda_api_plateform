<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Override;

/**
 * @implements ProviderInterface<Category>
 */
class CategoryAlphabeticalProvider implements ProviderInterface
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {}

    #[Override]
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        
        return $this->categoryRepository->findAllCategoryOrderAlphabetical($uriVariables["order"]);
    }
}
