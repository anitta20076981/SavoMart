<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attribute\AttributeAddRequest;
use App\Http\Requests\Admin\Attribute\AttributeCreateRequest;
use App\Http\Requests\Admin\Attribute\AttributeDeleteRequest;
use App\Http\Requests\Admin\Attribute\AttributeEditRequest;
use App\Http\Requests\Admin\Attribute\AttributeListDataRequest;
use App\Http\Requests\Admin\Attribute\AttributeListRequest;
use App\Http\Requests\Admin\Attribute\AttributeStatusChangeRequest;
use App\Http\Requests\Admin\Attribute\AttributeUpdateRequest;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AttributeController extends Controller
{
    private AttributeRepositoryInterface $attributeRepo;

    public function __construct(AttributeRepositoryInterface $attributeRepo)
    {
        $this->attributeRepo = $attributeRepo;
    }

    public function list(AttributeListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Attributes'],
        ];

        return view('admin.attribute.listAttributes', compact('breadcrumbs'));
    }

    public function attributeListData(AttributeListDataRequest $request)
    {
        $attributes = $this->attributeRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($attributes)
            ->addIndexColumn()
            ->addColumn('status', function ($attribute) {
                return view('admin.elements.listStatus')->with('data', $attribute);
            })
            ->editColumn('input_type', function ($attribute) {
                return attributeInputtype($attribute->input_type);
            })
            ->editColumn('is_required', function ($attribute) {
                return $attribute->is_required ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($attribute) use ($request) {
                $data['edit_url'] = request()->user()->can('attribute_update') ? route('admin_attribute_edit', ['id' => $attribute->id]) : '';
                $data['delete_url'] = request()->user()->can('attribute_delete') && !in_array($attribute->code, getDefaultAttributes()) ? route('admin_attribute_delete', ['id' => $attribute->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function addAttribute(AttributeAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_attribute_list', 'name' => 'Attribute', 'permission' => 'attribute_read'],
            ['name' => 'Add Attribute'],
        ];

        return view('admin.attribute.addAttribute', compact('breadcrumbs'));
    }

    public function createAttribute(AttributeCreateRequest $request)
    {
        $inputData = [
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->status,
            'input_type' => $request->input_type,
            'is_required' => $request->is_required ?? '0',
            'code' => $request->code ? Str::slug($request->code, '-') : Str::slug($request->name, '-'),
        ];

        $attribute = $this->attributeRepo->createAttribute($inputData);

        if ($request->has_options == '1' && $attribute) {
            $attributeOptions = $request->attribute_options;
            $this->_createAttributeOption($attribute->id, $attributeOptions);
        }

        return redirect()
            ->route('admin_attribute_list')
            ->with('success', 'Attribute added successfully');
    }

    public function editAttribute(AttributeEditRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_attribute_list', 'name' => 'Attributes', 'permission' => 'attribute_read'],
            ['name' => 'Attribute Details'],
        ];
        $attribute = $this->attributeRepo->getAttribute($request->id);

        return view('admin.attribute.editAttribute', compact('attribute', 'breadcrumbs'));
    }

    public function updateAttribute(AttributeUpdateRequest $request)
    {
        $attribute = $this->attributeRepo->getAttribute($request->id);
        $inputData = [
            'id' => $request->id,
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->status,
        ];

        if (!in_array($attribute->code, getDefaultAttributes())) {
            $inputData['input_type'] = $request->has('input_type') ? $request->input_type : $attribute->input_type;
            $inputData['code'] = $request->code ? Str::slug($request->code, '-') : Str::slug($request->name, '-');
        }

        $attribute = $this->attributeRepo->updateAttribute($inputData);

        if ($request->has_options == '1' && $attribute) {
            $attributeOptions = $request->attribute_options;
            $this->_updateAttributeOption($attribute->id, $attributeOptions);
        }
        return redirect()->route('admin_attribute_list')
            ->with('success', 'Attribute updated successfully');
    }

    public function statusChange(AttributeStatusChangeRequest $request)
    {
        if ($this->attributeRepo->attributeStatusUpdate($request->id)) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function deleteAttribute(AttributeDeleteRequest $request)
    {
        $attribute = $this->attributeRepo->getAttribute($request->id);

        if ($this->attributeRepo->deleteAttribute($request->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'Attribute deleted successfully']);
            } else {
                return redirect()->route('admin_attribute_list')->with('success', 'Attribute deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_attribute_list')->with('success', 'Failed to delete');
        }
    }

    private function _createAttributeOption($attributeId, $attributeOptions)
    {
        if (isset($attributeOptions)) {
            foreach ($attributeOptions as $attributeOption) {
                $input = [
                    'attribute_id' => $attributeId,
                    'swatch' => $attributeOption['swatch'] ? $attributeOption['swatch'] : '',
                    'label' => $attributeOption['label'],
                    'value' => $attributeOption['value'],
                    'label_ar' => $attributeOption['label_ar'],
                    'value_ar' => $attributeOption['value_ar'],
                ];
                $option = $this->attributeRepo->createAttributeOption($input);
            }
        }
    }

    private function _updateAttributeOption($attributeId, $attributeOptions)
    {
        $newAttributeOptions = [];

        if (isset($attributeOptions)) {
            $key = 1;

            foreach ($attributeOptions as $attributeOption) {
                $input = [
                    'id' => isset($attributeOption['id']) ? $attributeOption['id'] : 0,
                    'attribute_id' => $attributeId,
                    'swatch' => $attributeOption['swatch'] ? $attributeOption['swatch'] : '',
                    'label' => $attributeOption['label'],
                    'value' => $attributeOption['value'],
                    'label_ar' => $attributeOption['label_ar'],
                    'value_ar' => $attributeOption['value_ar'],
                ];
                $option = $this->attributeRepo->updateAttributeOption($input, $newAttributeOptions);
                array_push($newAttributeOptions, $option->id);
            }
        }
        $this->attributeRepo->deleteAttributeOption($attributeId, $newAttributeOptions);
    }
}