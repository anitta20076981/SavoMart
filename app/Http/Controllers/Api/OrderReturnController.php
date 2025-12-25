<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderReturn\OrderReturnAddRequest;
use App\Http\Requests\Api\OrderReturn\OrderReturnEditRequest;
use App\Http\Requests\Api\OrderReturn\OrderReturnListRequest;
use App\Http\Requests\Api\OrderReturn\OrderReturnDeleteRequest;
use App\Http\Requests\Api\OrderReturn\OrderReturnStatusChangeRequest;
use App\Repositories\OrderReturn\OrderReturnRepositoryInterface as OrderReturnRepository;
use App\Repositories\Order\OrderRepositoryInterface as OrderRepository;
use App\Repositories\Products\ProductsRepositoryInterface as ProductsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends Controller
{
    protected $orderReturnRepo;

    protected $orderRepo;

    protected $productRepo;

    public function __construct(
        OrderReturnRepository $orderReturnRepo,
        OrderRepository $orderRepo,
        ProductsRepository $productRepo,
    ) {
        $this->orderReturnRepo = $orderReturnRepo;
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
    }

    public function addReturn(OrderReturnAddRequest $request)
    {
        try {
            DB::beginTransaction();
            $orderReturn = [];

            $orderItem = $this->orderRepo->getOrderItem($request->order_item_id);

            if ($orderItem->orderReturnItem != null) {
                return $response = ['status' => false, 'message' => 'Return Already Added'];
            }
            if($orderItem->order->delivery_date != date('Y-m-d')){
                return $response = ['status' => false,  'message' => 'You can not return this item'];
            }
            $order = $this->orderRepo->get($orderItem->order_id);
            $inputData = [
                'order_id' => $order->id,
                'reason' => $request->reason,
                'location' => $request->location,
                'status' => 'pending',
                'order_item_id' => $request->order_item_id,
            ];
            $orderReturn = $this->orderReturnRepo->create($inputData);

            if ($request->hasFile('images')) {
                foreach ($request->images as $id => $file) {
                    $filePath = 'order_return/images';
                    $fileName = Storage::disk('savomart')->putFile($filePath, $file);
                    $imageFileName = explode('/', $fileName)[1];
                    $imagesUrl = Storage::disk('savomart')->url($fileName);

                    $inputImageData = [
                        'order_return_id' => $orderReturn->id,
                        'file' => $fileName,
                    ];
                    $orderReturnItems = $this->orderReturnRepo->saveReturnImages($inputImageData);
                }
            }

            //update return_status
            $updateData = [
                'id' => $request->order_item_id,
                'return_status' => 'return_placed',
            ];
            $this->orderRepo->updateOrderItem($updateData);

            $orderItem  = $this->orderRepo->getItems($order->id);
            $orderReturnItemsCount = $this->orderReturnRepo->getOrderReturnItemsCount($order->id);


            if (count($orderItem) == $orderReturnItemsCount) {
                $orderReturnStatusUpdate = [
                    'id' => $order->id,
                    'status' => 'returned',
                ];

                $this->orderRepo->update($orderReturnStatusUpdate);
            }

            $orderReturn = $this->orderReturnRepo->get($orderReturn->id);
            $data = compact('orderReturn');
            DB::commit();

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function editReturn(OrderReturnEditRequest $request)
    {
        try {
            DB::beginTransaction();
            $orderReturn = [];

            $orderReturn = $this->orderReturnRepo->get($request->order_return_id);
            $updateData = [
                'id' => $orderReturn->id,
                'reason' => ($request->reason) ? $request->reason : $orderReturn->reason,
                'location' => ($request->location) ? $request->location : $orderReturn->location,
            ];

            $orderReturn = $this->orderReturnRepo->update($updateData);

            if ($request->hasFile('images')) {
                $orderReturnItemsIds = [];

                foreach ($request->images as $id => $file) {
                    $filePath = 'order_return/images';
                    $fileName = Storage::disk('savomart')->putFile($filePath, $file);
                    $imageFileName = explode('/', $fileName)[1];
                    $imagesUrl = Storage::disk('savomart')->url($fileName);

                    $inputImageData = [
                        'order_return_id' => $orderReturn->id,
                        'file' => $fileName,
                    ];
                    $orderReturnItems = $this->orderReturnRepo->saveReturnImages($inputImageData);
                    $orderReturnItemsIds[] = $orderReturnItems->id;
                }
                $this->orderReturnRepo->deleteOrderReturnImages($orderReturn->id, $notIn = $orderReturnItemsIds);
            }
            $data = compact('orderReturn');
            DB::commit();

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            DB::rollBack();
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function listReturn(OrderReturnListRequest $request)
    {
        try {
            $orderReturnlist = [];
            $filterData['limit'] = $request->has('limit') && $request->limit ? $request->limit : 50;
            $filterData['page'] = $request->has('page') && $request->page ? $request->page : 1;
            $filterData['offset'] = ($filterData['page'] - 1) * $filterData['limit'];
            $filterData['customer_id'] = auth('sanctum')->user()->id;
            $orderReturn = $this->orderReturnRepo->orderReturnList($filterData);
            $data = compact('orderReturn');

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function deleteReturn(OrderReturnDeleteRequest $request)
    {
        try {
            $orderReturn = $this->orderReturnRepo->get($request->order_return_id);

            if ($orderReturn) {
                $this->orderReturnRepo->delete($orderReturn->id);
                $data = compact('orderReturn');

                return $response = ['status' => true, 'data' => $data,   'message' => 'Success'];
            } else {
                return $response = ['status' => false, 'message' => 'Invalid Order Return Id'];
            }
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

    public function statusChange(OrderReturnStatusChangeRequest $request)
    {
        try {
            $orderReturn = [];
            $orderReturn = $this->orderReturnRepo->get($request->order_return_id);
            $orderItem = $this->orderRepo->orderItems($orderReturn->order_item_id);

            if ($orderReturn->status == 'completed' && $request->status == 'confirmed') {
                return $response = ['status' => false, 'message' => 'Return status is completed so you can not change the status to confirmed'];
            } else {
                if ($request->status == 'completed') {

                    $incremetProductQty = $this->productRepo->incrementProductQty($orderItem->product->id, $orderItem->quantity);

                    $updateData = [
                        'id' => $orderItem->id,
                        'return_status' => 'return_completed',
                    ];
                    $this->orderRepo->updateOrderItem($updateData);
                }
                $input = [
                    'id' => $orderReturn->id,
                    'status' => $request->status,
                ];
                $retunStatusUpadate = $this->orderReturnRepo->update($input);
            }
            //order item status update
            $returnStatus = ($request->status == 'completed') ? 'return_completed' : (($request->status == 'confirmed') ? 'return_confirmed' : 'return_rejected');
            $orderItemUpdate = [
                'id' => $orderReturn->order_item_id,
                'return_status' => $returnStatus,
            ];
            $orderItemUpdate = $this->orderRepo->updateOrderItem($orderItemUpdate);



            $orderReturn = $this->orderReturnRepo->get($request->order_return_id);



            $data = compact('orderReturn');

            return $response = ['status' => true, 'data' => $data, 'message' => 'Success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];

            return response()->json($response, 200);
        }
    }

}
