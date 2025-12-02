<?php

namespace App\Http\Controllers\Admin\Includes\Banner;

use App\Repositories\Banner\BannerRepositoryInterface as BannerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Image
{
    public function imageSave(Request $request, BannerRepository $bannerRepo)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = 'banner';
            $fileName = Storage::disk('grocery')->putFile($filePath, $file);
            $imageFileName = explode('/', $fileName)[1];
            $imagesUrl = Storage::disk('grocery')->url($fileName);

            $inputImageData = [
                'file' => $file,
            ];
            $bannerItem = $bannerRepo->saveImage($inputImageData);
            $returnArray = [
                'id' => $bannerItem->id,
                'status' => true,
                'fileName' => $fileName,
                'imageUrl' => $imagesUrl,
                'imageFileName' => $request->file('file'),
            ];

            $returnArray['form'] = (string) view('admin.elements.dropzoneImageForm', compact('returnArray'));

            return response()->json($returnArray, 200);
        }

        return response()->json('Upload Failed!', 400);
    }

    public function imageData(BannerRepository $bannerRepo, Request $request)
    {
        $banner = $bannerRepo->getBanner($request->banner_id);
        $items = [];

        foreach ($banner->items as $key => &$item) {
            $item->url = Storage::disk('grocery')->url($item->file);
            $returnArray = [
                'id' => $item->id,
                'fileName' => $item->file,
                'link' => $item->link,
                'title' => $item->title,
            ];
            $item->form = (string) view('admin.elements.dropzoneImageForm', compact('returnArray'));
        }

        return $banner->items;
    }

    public function imageDelete(Request $request, BannerRepository $bannerRepo)
    {
        $bannerItem = $bannerRepo->getBannerItem($request->id);

        if (!$bannerItem->banner_id) {
            if (Storage::disk('grocery')->delete($request->file)) {
                $bannerRepo->deleteImage($request->id, $request->file);
            }
        }
    }

    public function linkUpdate(Request $request, BannerRepository $bannerRepo)
    {
        $updateData = [
            'id' => $request->id,
            'link' => $request->link,
            'title' => $request->title,
        ];

        $bannerRepo->updateBannerItems(($updateData));

        return response()->json(['status' => true, 'message' => 'Link Updated'], 200);
    }
}