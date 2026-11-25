<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\State\CategoryAlphabeticalProvider;
use Symfony\Component\Serializer\Attribute\Groups;
use App\State\CategoryBooksProvider;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/category/{id}',
            requirements: ['id' => '\d+'],
        ),
        new GetCollection(
            uriTemplate: '/categories',
        ),
        new Post(
            uriTemplate: '/category',
            status: 301
        ),
        new GetCollection(
            uriTemplate: '/categories/alphabetical/{order}',
            uriVariables: ['order' => 'string'],
            provider: CategoryAlphabeticalProvider::class,
            normalizationContext: ['groups' => ['category:list']],
            outputFormats: ['json' => ['application/json']]
        ),
        new GetCollection(
            uriTemplate: '/categories/{id}/books',
            uriVariables: ['id' => 'int'],
            provider: CategoryBooksProvider::class,
            normalizationContext: ['groups' => ['category:list']],
            outputFormats: ['json' => ['application/json']]
        )
    ],
    paginationEnabled: false
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    #[Groups(['category:list'])]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
