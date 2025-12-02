<?php

namespace App\View\Components;

use App\Repositories\Cart\CartRepositoryInterface as CartRepository;
use App\Repositories\Category\CategoryRepositoryInterface as categoryRepository;
use Illuminate\View\Component;

class Layout extends Component
{
    public function __construct(CartRepository $cartRepo, categoryRepository $categoryRepo)
    {
        $this->cartRepo = $cartRepo;
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('web.layouts.app');
    }
}
