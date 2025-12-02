<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Pages\PagesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{

    private PagesRepositoryInterface $pagesRepo;

    public function __construct( PagesRepositoryInterface $pagesRepo)
    {
        $this->pagesRepo = $pagesRepo;
    }



    public function termsAndCondition( )
    {
        $termsAndCondition = $this->pagesRepo->getPageBySlug('terms-and-conditions');

        return response()->json(['status' => true, 'termsAndCondition' => $termsAndCondition], 200);

    }



}
