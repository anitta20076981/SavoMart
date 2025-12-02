<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Banner\BannerRepositoryInterface as BannerRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CmsController extends Controller
{
    public function getHomeBanner(Request $request, BannerRepository $bannerRepo)
    {
        $banners = $bannerRepo->getBannerByslug('home-banner');
        return response()->json(['status' => true, 'banner' => $banners], 200);
    }

}
