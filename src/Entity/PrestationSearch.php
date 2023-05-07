<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class PrestationSearch
{
    private ?string $name = '';

    private int $page = 1;

    private mixed $categories;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCategories(): mixed
    {
        return $this->categories;
    }

    public function setCategories(Category $categories): void
    {
        $this->categories = $categories;
    }
}
