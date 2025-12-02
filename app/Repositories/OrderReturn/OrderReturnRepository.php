<?php

namespace App\Repositories\OrderReturn;

use App\Models\OrderReturnImage;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class OrderReturnRepository implements OrderReturnRepositoryInterface
{
    public function saveReturnImages($input)
    {
        if (isset($input['id']) && $input['id']) {
            $orderReturnImage = OrderReturnImage::find($input['id']);
            $orderReturnImage->update($input);

            return $orderReturnImage;
        } elseif ($orderReturnImage = OrderReturnImage::create($input)) {
            return $orderReturnImage;
        }

        return false;
    }

    public function create($input)
    {
        if ($orderReturn = OrderReturn::create($input)) {
            return $orderReturn;
        }

        return false;
    }

    public function getOrderReturnItemsCount($orderId)
    {
        return OrderReturn::where('order_id', $orderId)->count();
    }

    public function getForDatatable($data)
    {
        return OrderReturn::with(['order', 'order.customer'])
            ->select(['order_returns.*'])
            ->orderBy('order_returns.created_at', 'desc')
            ->where(function (Builder $query) use ($data) {
                if ($data['status'] != '') {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function get($returnId)
    {
        return OrderReturn::with(['items', 'items.products', 'orderReturnImages', 'orderItem'])->findOrFail($returnId);
    }

    public function getImage($id)
    {
        return $orderReturnImage = OrderReturnImage::find($id);
    }

    public function deleteImage($id, $fileName)
    {
        return OrderReturnImage::where('id', $id)->where('file', $fileName)->delete();
    }

    public function update(array $input)
    {
        $orderReturn = OrderReturn::find($input['id']);
        unset($input['id']);

        if ($orderReturn->update($input)) {
            return $orderReturn;
        }

        return false;
    }

    public function deleteOrderReturnImages($bannerId, $notInIds)
    {
        $items = OrderReturnImage::whereNotIn('id', $notInIds)->where('order_return_id', $bannerId)->get();

        foreach ($items as $item) {
            if (Storage::disk('grocery')->delete($item->file)) {
                $item->delete();
            }
        }
    }

    public function getReturnItemCount($orderId)
    {
        return OrderReturn::where('order_id', $orderId)->where('status', 'completed')->count();
    }

    public function orderReturnList($filterData)
    {
        $orderReturn = OrderReturn::with('orderReturnImages', 'order');
        $orderReturn = $orderReturn->whereHas('order', function ($query) use ($filterData) {
            return $query->where('customer_id', $filterData['customer_id']);
        });

        if (isset($filterData['offset']) && isset($filterData['limit'])) {
            $orderReturn = $orderReturn->offset($filterData['offset'])->limit($filterData['limit']);
        }

        return $orderReturn->get();
    }

    public function delete($orderReturnId)
    {
        $orderReturn = OrderReturn::with(['orderReturnImages'])->find($orderReturnId);
        $orderReturn->orderReturnImages()->delete();

        return $orderReturn->delete();
    }
}
