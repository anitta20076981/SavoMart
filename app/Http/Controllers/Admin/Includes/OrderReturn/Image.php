<?php

namespace App\Http\Controllers\Admin\Includes\OrderReturn;
use App\Repositories\OrderReturn\OrderReturnRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait Image
{
    public function saveImage(Request $request, OrderReturnRepository $orderReturnRepo)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = 'order_return/images';
            $fileName = Storage::disk('savomart')->putFile($filePath, $file);
            $imageFileName = explode('/', $fileName)[1];
            $imagesUrl = Storage::disk('savomart')->url($fileName);

            $inputImageData = [
                'file' => $fileName,
            ];
            $orderReturnItems = $orderReturnRepo->saveReturnImages($inputImageData);
            $returnArray = [
                'id' => $orderReturnItems->id,
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

    public function imageData(OrderReturnRepository $orderReturnRepo, Request $request)
    {
        $orderReturns = $orderReturnRepo->get($request->order_return_id);
        $items = [];

        foreach ($orderReturns->orderReturnImages as $key => &$item) {
            $item->url = Storage::disk('savomart')->url($item->file);
            $returnArray = [
                'id' => $item->id,
                'fileName' => $item->file,
            ];
            $item->form = (string) view('admin.elements.dropzoneImageForm', compact('returnArray'));
        }

        return $orderReturns->orderReturnImages;
    }

    public function imageDelete(Request $request, OrderReturnRepository $orderReturnRepo)
    {
        $image = $orderReturnRepo->getImage($request->id);
        if ($image->order_return_id) {
            if (Storage::disk('savomart')->delete($image->file)) {
                $orderReturnRepo->deleteImage($request->id, $image->file);
            }
        }
    }
}