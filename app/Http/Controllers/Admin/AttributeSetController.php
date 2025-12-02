<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeSet\AttributeSetAddRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetCreateRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetDeleteRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetEditRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetListDataRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetListRequest;
use App\Http\Requests\Admin\AttributeSet\AttributeSetUpdateRequest;
use App\Repositories\Attribute\AttributeRepositoryInterface;
use App\Repositories\AttributeSet\AttributeSetRepositoryInterface;
use Yajra\DataTables\DataTables;

class AttributeSetController extends Controller
{
    private AttributeSetRepositoryInterface $attributeSetRepo;

    private AttributeRepositoryInterface $attributeRepo;

    public function __construct(
        AttributeSetRepositoryInterface $attributeSetRepo,
        AttributeRepositoryInterface $attributeRepo,
    ) {
        $this->attributeSetRepo = $attributeSetRepo;
        $this->attributeRepo = $attributeRepo;
    }

    public function list(AttributeSetListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'AttributeSets'],
        ];

        return view('admin.attributeSet.listAttributeSets', compact('breadcrumbs'));
    }

    public function attributeListData(AttributeSetListDataRequest $request)
    {
        $attributeSets = $this->attributeSetRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($attributeSets)
            ->addIndexColumn()
            ->addColumn('status', function ($attributeSet) {
                return view('admin.elements.listStatus')->with('data', $attributeSet);
            })
            ->addColumn('action', function ($attributeSet) use ($request) {
                $data['edit_url'] = request()->user()->can('attribute_set_update') ? route('admin_attribute_set_edit', ['id' => $attributeSet->id]) : '';
                $data['delete_url'] = request()->user()->can('attribute_set_delete') && $attributeSet->id != 1 ? route('admin_attribute_set_delete', ['id' => $attributeSet->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function addAttributeSet(AttributeSetAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_attribute_set_list', 'name' => 'AttributeSet', 'permission' => 'attribute_read'],
            ['name' => 'Add AttributeSet'],
        ];

        return view('admin.attributeSet.addAttributeSet', compact('breadcrumbs'));
    }

    public function createAttributeSet(AttributeSetCreateRequest $request)
    {
        $inputData = [
            'name' => $request->name,
            'status' => $request->status,
        ];

        $attributeSet = $this->attributeSetRepo->createAttributeSet($inputData);

        $defaultAttributes = getDefaultAttributes();

        if ($defaultAttributes) {
            foreach ($defaultAttributes as $defaultAttribute) {
                $attribute = $this->attributeRepo->getAttributeByCode($defaultAttribute);

                if ($attribute) {
                    $input = [
                        'attribute_set_id' => $attributeSet->id,
                        'attribute_id' => $attribute->id,
                    ];
                    $attributeSetAttribute = $this->attributeSetRepo->createAttributeSetAttribute($input);
                }
            }
        }

        return redirect()->route('admin_attribute_set_edit', ['id' => $attributeSet->id])->with('success', 'AttributeSet added successfully');
    }

    public function editAttributeSet(AttributeSetEditRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_attribute_set_list', 'name' => 'AttributeSets', 'permission' => 'attribute_read'],
            ['name' => 'AttributeSet Details'],
        ];

        $attributeSet = $this->attributeSetRepo->getAttributeSet($request->id);
        $availableAttributes = [];

        if (count($attributeSet->attributes) && isset($attributeSet->attributes)) {
            foreach ($attributeSet->attributes as $attribute) {
                $availableAttributes[] = $attribute->id;
            }
        }

        $unAssignedAttributes = $this->attributeRepo->getAttributesForAttributeSet($availableAttributes);

        return view('admin.attributeSet.editAttributeSet', compact('attributeSet', 'breadcrumbs', 'unAssignedAttributes'));
    }

    public function updateAttributeSet(AttributeSetUpdateRequest $request)
    {
        $inputData = [
            'id' => $request->id,
            'name' => $request->name,
            'status' => $request->status,
        ];

        $attributeSet = $this->attributeSetRepo->updateAttributeSet($inputData);

        $newAttribute = [];

        if (isset($request->assigned_attributes) && $attributeSet) {
            foreach ($request->assigned_attributes as $assignedAttributes) {
                $input = [
                    'attribute_set_id' => $attributeSet->id,
                    'attribute_id' => $assignedAttributes,
                ];
                $attribute = $this->attributeSetRepo->updateAttributeSetAttribute($input);
                array_push($newAttribute, $attribute->id);
            }
        }
        $this->attributeSetRepo->deleteAttributeSetAttribute($attributeSet->id, $newAttribute);

        return redirect()
            ->route('admin_attribute_set_list')
            ->with('success', 'AttributeSet updated successfully');
    }

    public function deleteAttributeSet(AttributeSetDeleteRequest $request)
    {
        $attribute = $this->attributeSetRepo->getAttributeSet($request->id);

        if ($this->attributeSetRepo->deleteAttributeSet($request->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'AttributeSet deleted successfully']);
            } else {
                return redirect()->route('admin_attribute_set_list')->with('success', 'AttributeSet deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_attribute_set_list')->with('success', 'Failed to delete');
        }
    }
}
