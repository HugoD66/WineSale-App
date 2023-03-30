<?php

namespace  App\Components;

use App\Entity\Wine;
use App\Repository\WineRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;


#[AsTwigComponent('all_wines')]
class AllWinesComponent
{
    public int $id;
    public function __construct(
        private WineRepository $wineRepository
    ) {

    }
    public function getAllWines (): array
    {
        return $this->wineRepository->findAll();
    }
}