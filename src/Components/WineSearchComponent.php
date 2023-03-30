<?php
namespace App\Components;

use App\Repository\WineRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('wines_search')]
class WineSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private WineRepository $wineRepository
    ) {
    }

    public function getWines(): array
    {
        return $this->wineRepository->findByQuery($this->query);
    }
    public function getAllWines (): array
    {
        return $this->wineRepository->findAll();
    }
}