<?php

namespace App\Http\Controllers\Admin\Includes\Product;

use App\Repositories\Products\ProductsRepositoryInterface as ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Image
{
    public function saveImage(Request $request, ProductRepository $productRepo)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = 'products/images';
            $fileName = Storage::disk('grocery')->putFile($filePath, $file);
            $imageFileName = explode('/', $fileName)[1];
            $imagesUrl = Storage::disk('grocery')->url($fileName);

            $inputImageData = [
                'file' => $file,
                'image_path' => $fileName,
                'image_role' => 'BASE',
            ];
            $productItem = $productRepo->saveImages($inputImageData);
            $returnArray = [
                'id' => $productItem->id,
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

    public function imageData(ProductRepository $productsRepo, Request $request)
    {
        $products = $productsRepo->get($request->product_id);
        $items = [];

        foreach ($products->productImages as $key => $item) {
            $item->url = Storage::disk('grocery')->url($item->image_path);
            $returnArray = [
                'id' => $item->id,
                'fileName' => $item->image_path,
            ];
            $item->form = (string) view('admin.elements.dropzoneImageForm', compact('returnArray'));
        }

        return $products->productImages;
    }

    public function imageDelete(Request $request, ProductRepository $productsRepo)
    {
        $image = $productsRepo->getImage($request->id);

        if (!$image->product_id) {
            if (Storage::disk('grocery')->delete($image->image_path)) {
                $productsRepo->deleteImage($request->id, $image->image_path);
            }
            $event = auth()->user()->name . 'deleted the product image';
       
        }
    }
}
