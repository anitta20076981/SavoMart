<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CategoryAddRequest;
use App\Http\Requests\Admin\Category\CategoryCreateRequest;
use App\Http\Requests\Admin\Category\CategoryDeleteRequest;
use App\Http\Requests\Admin\Category\CategoryEditRequest;
use App\Http\Requests\Admin\Category\CategoryListDataRequest;
use App\Http\Requests\Admin\Category\CategoryListRequest;
use App\Http\Requests\Admin\Category\CategoryUpdateRequest;
use App\Repositories\Category\CategoryRepositoryInterface as CategoryRepository;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function list(CategoryListRequest $request, CategoryRepository $categoryRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Categories'],
        ];
        $categories = $categoryRepo->getForDataTable($request->all())->get();

        return view('admin.category.listCategory', compact('breadcrumbs','categories'));
    }

    public function table(CategoryListDataRequest $request, CategoryRepository $categoryRepo)
    {
        $categories = $categoryRepo->getForDataTable($request->all());
        $dataTableJSON = DataTables::of($categories)
            ->addIndexColumn()
            ->editColumn('name', function ($category) {
                $data['url'] = request()->user()->can('categories_update') ? route('admin_categories_edit', ['id' => $category->id]) : '';
                $data['text'] = $category->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->editColumn('name_ar', function ($category) {
                $data['url'] = request()->user()->can('categories_update') ? route('admin_categories_edit', ['id' => $category->id]) : '';
                $data['text'] = $category->name_ar;

                return view('admin.elements.listLink', compact('data'));
            })
            ->editColumn('icon', function ($category) {
                $data['src'] = $category->icon && Storage::disk('savomart')->exists($category->icon) ? Storage::disk('savomart')->url($category->icon) : asset('images/admin/svg/files/blank-image.svg');

                return view('admin.elements.listImage', compact('data'));
            })
            ->addColumn('parent_category', function ($category) {
                return $category->parentCategory ? $category->parentCategory->name : '';
            })
            ->addColumn('status', function ($category) {
                return view('admin.elements.listStatus')->with('data', $category);
            })
            ->addColumn('action', function ($category) use ($request) {
                $data['edit_url'] = request()->user()->can('categories_update') ? route('admin_categories_edit', ['id' => $category->id]) : '';
                $data['delete_url'] = request()->user()->can('categories_delete') ? route('admin_categories_delete', ['id' => $category->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(CategoryAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_categories_list', 'name' => 'Categories', 'permission' => 'categories_read'],
            ['name' => 'Categories'],
        ];

        return view('admin.category.addCategories', compact('breadcrumbs'));
    }

    public function save(CategoryCreateRequest $request, CategoryRepository $categoryRepo)
    {
        $input = [
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->status,
            'parent_category_id' => (isset($request->parent_category)) ? $request->parent_category : 1,
            'logo' => '',
        ];

        if ($request->hasFile('icon')) {
            $filePath = 'category/icon';
            $input['icon'] = Storage::disk('savomart')->putFile($filePath, $request->file('icon'));
        } else {
            $input['icon'] = '';
        }
        $category = $categoryRepo->save($input);

        $event = auth()->user()->name . ' Added category with name ' . $request->name;

        return redirect()
            ->route('admin_categories_list')
            ->with('success', 'Category added successfully');
    }

    public function edit(CategoryEditRequest $request, CategoryRepository $categoryRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_categories_list', 'name' => 'Categories', 'permission' => 'categories_update'],
            ['name' => 'Categories'],
        ];
        $category = $categoryRepo->get($request->id);

        if (old('category_id', $category->parent_category_id)) {
            $categoryParent = $categoryRepo->get(old('category_id', $category->parent_category_id));
        } else {
            $categoryParent = '';
        }

        return view('admin.category.editCategories', compact('breadcrumbs', 'category', 'categoryParent'));
    }

    public function update(CategoryUpdateRequest $request, CategoryRepository $categoryRepo)
    {
        $input = [
            'id' => $request->id,
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'status' => $request->status,
            'parent_category_id' => (isset($request->parent_category_id)) ? $request->parent_category_id : 1,
            'logo' => '',
        ];

        if ($request->hasFile('icon')) {
            $filePath = 'category/icon';
            $input['icon'] = Storage::disk('savomart')->putFile($filePath, $request->file('icon'));
        } elseif ($request->has('icon_remove') && $request->icon_remove) {
            $input['icon'] = '';
        }
        $category = $categoryRepo->update($input);

        $event = auth()->user()->name . ' Updated the category with name ' . $request->name;

        return redirect()
            ->route('admin_categories_list')
            ->with('success', 'Category added successfully');
    }

    public function delete(CategoryDeleteRequest $request, CategoryRepository $categoryRepo)
    {
        $category = $categoryRepo->get($request->id);

        if ($categoryRepo->delete($request->id)) {
            $event = auth()->user()->name . ' Deleted the Category with Name ' . $category->name;

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function treeForm(Request $request, CategoryRepository $categoryRepo)
    {
        $categories = $categoryRepo->getTree();
        $selectedCategories = [];
        $responce['html'] = (string) view('admin.category.treeForm', compact('categories', 'selectedCategories'));
        $responce['scripts'][] = (string) asset('js/admin/category/treeForm.js');

        return $responce;
    }
}