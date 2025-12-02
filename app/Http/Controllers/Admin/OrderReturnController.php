<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Includes\OrderReturn\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderReturn\OrderReturnCreateRequest;
use App\Http\Requests\Admin\OrderReturn\OrderReturnDeleteRequest;
use App\Http\Requests\Admin\OrderReturn\OrderReturnEditRequest;
use App\Http\Requests\Admin\OrderReturn\OrderReturnListDataRequest;
use App\Http\Requests\Admin\OrderReturn\OrderReturnListRequest;
use App\Http\Requests\Admin\OrderReturn\OrderReturnUpdateRequest;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\CustomerTransaction\CustomerTransactionRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderReturn\OrderReturnRepository;
use App\Repositories\Products\ProductsRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use stdClass;
use Yajra\DataTables\DataTables;

class OrderReturnController extends Controller
{
    use Image;

    public function list(OrderReturnListRequest $request)
    {
        $breadcrumbs = [
            ['name' => 'Order -Return'],
        ];

        return view('admin.orderReturn.listOrderReturn', compact('breadcrumbs'));
    }

    public function table(OrderReturnListDataRequest $request, OrderReturnRepository $orderReturnRepo)
    {
        $orderReturns = $orderReturnRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($orderReturns)
            ->addIndexColumn()
            ->editColumn('return_status', function ($orderReturn) {
                return view('admin.orderReturn.returnStatus')->with('data', $orderReturn);
            })
            ->addColumn('action', function ($orderReturn) use ($request) {
                $data['edit_url'] = request()->user()->can('order_return_update') ? route('admin_order_return_edit', ['id' => $orderReturn->id]) : '';

                $data['delete_url'] = request()->user()->can('order_return_delete') && $orderReturn->status != 'completed' ? route('admin_order_return_delete', ['id' => $orderReturn->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(
        Request $request,
        OrderReturnRepository $orderReturnRepo,
        OrderRepository $orderRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_order_return_list', 'name' => 'Order - Return', 'permission' => 'order_return_read'],
            ['name' => 'Add Order Return'],
        ];
        $retunItemDetails = $orderRepo->getOrderItem($request->order_item_id);
        $old = [];
        return view('admin.orderReturn.addOrderReturn', compact('breadcrumbs', 'retunItemDetails'));
    }

    public function productsTable(
        OrderReturnListDataRequest $request,
        OrderReturnRepository $orderReturnRepo,
        OrderRepository $orderRepo
    ) {
        $orderItems = $orderRepo->getAllDispachedOrders($request->all());
        $dataTableJSON = DataTables::of($orderItems)
            ->addIndexColumn()
            ->addColumn('product', function ($orderItem) use ($orderReturnRepo) {
                return view('admin.orderReturn.productList', compact('orderItem'));
            })
            ->editColumn('order_quantity', function ($orderItem) {
                return $orderItem->quantity;
            })
            ->addColumn('return_status', function ($orderItem) {
                if (isset($orderItem->orderReturnItem) && $orderItem->orderReturnItem != null) {
                    return 'yes';
                } else {
                    return 'No';
                }
            })
            ->make();

        return $dataTableJSON;
    }

    public function save(
        OrderReturnCreateRequest $request,
        OrderReturnRepository $orderReturnRepo,
        ProductsRepository $productRepo,
        OrderRepository $orderRepo
    ) {
        try {
            DB::beginTransaction();

            $inputData = [
                'order_id' => $request->order_id,
                'reason' => $request->reason,
                'location' => $request->location,
                'status' => 'pending',
                'order_item_id' => $request->order_item_id,
            ];
            $orderReturn = $orderReturnRepo->create($inputData);

            if ($request->has('images')) {
                foreach ($request->images as $id => $file) {
                    $OrderReturnImages = [
                        'id' => $id,
                        'order_return_id' => $orderReturn->id,
                        'file' => $file,
                    ];
                    $OrderReturnImage = $orderReturnRepo->saveReturnImages($OrderReturnImages);
                }
            }
            //update return_status
            $updateData = [
                'id' => $request->order_item_id,
                'return_status' => 'return_placed',
            ];
            $orderRepo->updateOrderItem($updateData);

            $orderItem  = $orderRepo->getItems($request->order_id);
            $orderReturnItemsCount = $orderReturnRepo->getOrderReturnItemsCount($request->order_id);

            if (count($orderItem) == $orderReturnItemsCount) {
                $orderReturnStatusUpdate = [
                    'id' => $request->order_id,
                    'status' => 'returned',
                ];

                $orderRepo->update($orderReturnStatusUpdate);
            }

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();

        return redirect()
            ->route('admin_order_return_list')
            ->with('success', 'Order Return added successfully');
    }

    public function edit(
        OrderReturnEditRequest $request,
        OrderReturnRepository $orderReturnRepo,
        OrderRepository $orderRepo,

    ) {
        $breadcrumbs = [
            ['link' => 'admin_order_return_list', 'name' => 'Order - Return', 'permission' => 'order_returns_read'],
            ['name' => 'Order Return Details'],
        ];
        $orderReturn = $orderReturnRepo->get($request->id);
        $order = $orderRepo->get(old('order_id', $orderReturn->order_id));
        $orderItem = $orderRepo->orderItems($orderReturn->order_item_id);

        return view('admin.orderReturn.editOrderReturn', compact('orderReturn', 'breadcrumbs', 'order', 'orderItem'));
    }

    public function editproductsTable(
        Request $request,
        OrderRepository $orderRepo,
        OrderReturnRepository $orderReturnRepo
    ) {
        $orderItems = $orderRepo->getItems($request->order_id);
        $orderReturn = $orderReturnRepo->get($request->order_return_id);
        $orderItems = $orderRepo->orderItem($orderReturn->order_item_id);
        $dataTableJSON = DataTables::of($orderItems)
            ->addIndexColumn()
            ->addColumn('product', function ($orderItem) {
                return view('admin.orderReturn.productList', compact('orderItem'));
            })
            ->editColumn('order_quantity', function ($orderItem) {
                return $orderItem->quantity;
            })
            ->addColumn('return_status', function ($orderItem) use ($orderReturnRepo, $request) {
                $orderReturn = $orderReturnRepo->getReturnFormOrderItemId($orderItem->id, $request->order_id);

                if ($orderReturn != null) {
                    return 'yes';
                } else {
                    return 'No';
                }
            })
            ->make();

        return $dataTableJSON;
    }

    public function update(
        OrderReturnUpdateRequest $request,
        OrderReturnRepository $orderReturnRepo
    ) {
        $updateData = [
            'id' => $request->id,
            'reason' => $request->reason,
            'location' => $request->location,
        ];

        $orderReturn = $orderReturnRepo->update($updateData);

        if (isset($request->images) && $request->images) {
            $orderReturnItemsIds = [];

            if ($request->has('images')) {
                foreach ($request->images as $id => $file) {
                    $inputImageData = [
                        'id' => $id,
                        'order_return_id' => $orderReturn->id,
                        'file' => $file,
                    ];
                    $orderReturnItem = $orderReturnRepo->saveReturnImages($inputImageData);
                    $orderReturnItemsIds[] = $orderReturnItem->id;
                }
            }
            $orderReturnRepo->deleteOrderReturnImages($orderReturn->id, $notIn = $orderReturnItemsIds);
        }

        return redirect()
            ->route('admin_order_return_list')
            ->with('success', 'Order Return updated successfully');
    }

    public function delete(
        OrderReturnRepository $orderReturnRepo,
        OrderReturnDeleteRequest $request,
        OrderRepository $orderRepo
    ) {
        $orderReturn = $orderReturnRepo->get($request->id);
        activity('Order Return')->performedOn($orderReturn)->event('Order Return Deleted')->withProperties(['order_return_id' => $orderReturn->id])->log('Order Return Deleted');

        if ($orderReturnRepo->delete($request->id)) {
            $orderItemsCount = $orderRepo->getOrderItemsCount($orderReturn->order_id);
            $orderReturnItemsCount = $orderReturnRepo->getOrderReturnItemsCount($orderReturn->order_id);

            if ($orderItemsCount != $orderReturnItemsCount) {
                $orderReturnStatusUpdate = [
                    'id' => $orderReturn->order_id,
                    'return_status' => 'no',
                ];

                $orderRepo->update($orderReturnStatusUpdate);
            }

            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'Order Return deleted successfully']);
            } else {
                return redirect()->route('admin_order_list')->with('success', 'Order Return deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_order_return_list')->with('success', 'Failed to delete');
        }
    }

    public function statusUpdate(OrderReturnEditRequest $request,
        OrderReturnRepository $orderReturnRepo,
        OrderRepository $orderRepo,
        CustomerRepository $customerRepo,
        ProductsRepository $productRepo)
    {
        $orderReturn = $orderReturnRepo->get($request->id);
        $orderItem = $orderRepo->orderItems($orderReturn->order_item_id);

        if ($request->returnStatus == 'completed') {
            $incremetProductQty = $productRepo->incrementProductQty($orderItem->product->id, $orderItem->quantity);

            $updateData = [
                'id' => $orderItem->id,
                'return_status' => 'return_completed',
            ];
            $orderRepo->updateOrderItem($updateData);
        }

        if ($request->returnStatus == 'rejected') {
            if ($orderItem->status == 'rejected') {
                return response()->json(['status' => 0, 'message' => 'This Item Is Already Rejected']);
            }
            $input = [
                'id' => $orderReturn->order_id,
                'return_status' => 'no',
            ];
            $orderRepo->update($input);
            $input = [
                'id' => $request->id,
                'status' => $request->returnStatus,
                'reject_reason' => $request->has('rejectReason') ? $request->rejectReason : '',
            ];
            $retunStatusUpadate = $orderReturnRepo->update($input);
            $updateData = [
                'id' => $orderItem->id,
                'return_status' => 'return_rejected',
            ];
            $orderRepo->updateOrderItem($updateData);
        }

        if($request->returnStatus == 'confirmed')
        {
            $updateData = [
                'id' => $orderItem->id,
                'return_status' => 'return_confirmed',
            ];
            $orderRepo->updateOrderItem($updateData);
        }

        $input = [
            'id' => $request->id,
            'status' => $request->returnStatus,
        ];
        $retunStatusUpadate = $orderReturnRepo->update($input);

        //order status update
        $returnItemCount = $orderReturnRepo->getReturnItemCount($orderItem->order_id);

        if (count($orderItem->order->orderItems) == $returnItemCount) {
            $orderStatusUpdate = [
                'id' => $orderItem->order_id,
                'status' => 'returned',
            ];
            $orderStatusUpdate = $orderRepo->update($orderStatusUpdate);
        }

        if ($retunStatusUpadate) {
            return response()->json(['status' => 1, 'message' => ' Status changed successfully']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Oops! Something went wrong']);
        }
    }
}
