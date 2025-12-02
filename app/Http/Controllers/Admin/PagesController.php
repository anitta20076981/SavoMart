<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\FaqCreateRequest;
use App\Http\Requests\Admin\Pages\FaqUpdateRequest;
use App\Http\Requests\Admin\Pages\PagesAddRequest;
use App\Http\Requests\Admin\Pages\PagesCreateRequest;
use App\Http\Requests\Admin\Pages\PagesDeleteRequest;
use App\Http\Requests\Admin\Pages\PagesListDataRequest;
use App\Http\Requests\Admin\Pages\PagesListRequest;
use App\Http\Requests\Admin\Pages\PagesUpdateRequest;
use App\Repositories\Pages\PagesRepositoryInterface as PagesRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PagesController extends Controller
{

    public function list(PagesListRequest $request)
    {
        $breadcrumbs = [
            ['name' => 'Pages'],
        ];
        $categoryId = '';

        if ($request->category == 'faq') {
            $categoryId = 1;
        }

        return view('admin.pages.listPages', compact('breadcrumbs', 'categoryId'));
    }

    public function table(PagesListDataRequest $request, PagesRepository $pagesRepo)
    {
        $pages = $pagesRepo->getPages($request->all());
        $dataTableJSON = DataTables::of($pages)
            ->addIndexColumn()
            ->editColumn('name', function ($page) {
                $data['url'] = request()->user()->can('pages_view') ? route('admin_pages_edit', ['id' => $page->id]) : '';
                $data['text'] = $page->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('status', function ($page) {
                return view('admin.elements.listStatus')->with('data', $page);
            })
            ->addColumn('action', function ($page) use ($request) {
                $data['edit_url'] = request()->user()->can('pages_update') ? route('admin_pages_edit', ['id' => $page->id]) : '';
                $data['delete_url'] = request()->user()->can('pages_delete') && $page->is_deletable == 1 ? route('admin_pages_delete', ['id' => $page->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(PagesAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_pages_list', 'name' => 'Pages', 'permission' => 'pages_read'],
            ['name' => 'Add Page'],
        ];

        return view('admin.pages.addPages', compact('breadcrumbs'));
    }

    public function save(PagesCreateRequest $request, PagesRepository $pagesRepo)
    {
        $data = [];
        $slug = $request->slug != null ? $request->slug : Str::slug($request->title);
        $data = [
            'name' => $request->name,
            'title' => $request->title,
            'status' => $request->status,
            'slug' => $slug,
            'content' => $request->content,
            'is_deltetable' => 1,
        ];

        if ($request->hasFile('file')) {
            $filePath = 'pages/file';
            $fileName = Storage::disk('grocery')->putFile($filePath, $request->file('file'));
            $data['file'] = $fileName;
        }

        if ($request->hasFile('thumbnail')) {
            $filePath = 'pages/thumbnail';
            $data['thumbnail'] = Storage::disk('grocery')->putFile($filePath, $request->file('thumbnail'));
        } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
            $data['thumbnail'] = '';
        }
        $page = $pagesRepo->save($data);
        $event = auth()->user()->name . ' Added the Page with Title ' . $request->title;
        activity('Pages')->performedOn($page)->event($event)->withProperties(['page_id' => $page->id, 'data' => $request->all()])->log('Page Created');

        return redirect()->route('admin_pages_list')->with('success', 'Page added successfully');
    }

    public function edit(PagesAddRequest $request, PagesRepository $pagesRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_pages_list', 'name' => 'Pages', 'permission' => 'pages_update'],
            ['name' => 'Edit Page'],
        ];
        $pages = $pagesRepo->get($request->id);

        return view('admin.pages.editPages', compact('breadcrumbs', 'pages'));
    }

    public function update(PagesUpdateRequest $request, PagesRepository $pagesRepo)
    {
        $currentData = $pagesRepo->get($request->id);
        $slug = $request->slug != null ? $request->slug : $currentData->slug;
        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'title' => $request->title,
            'status' => $request->status,
            'slug' => $slug,
            'content' => $request->content,
        ];

        if ($request->hasFile('file')) {
            $filePath = 'pages/file';
            $fileName = Storage::disk('grocery')->putFile($filePath, $request->file('file'));
            $data['file'] = $fileName;
        } elseif ($request->has('file_remove') && $request->file_remove) {
            $data['file'] = '';
        }

        if ($request->hasFile('thumbnail')) {
            $filePath = 'pages/thumbnail';
            $data['thumbnail'] = Storage::disk('grocery')->putFile($filePath, $request->file('thumbnail'));
        } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
            $data['thumbnail'] = '';
        }
        $page = $pagesRepo->update($data);

        /***multiple images */
        if (isset($request->images) && $request->images) {
            $pageItemIds = [];

            if ($request->has('images')) {
                foreach ($request->images as $id => $file) {
                    $inputImageData = [
                        'id' => $id,
                        'page_id' => $page->id,
                        'file' => $file,
                    ];
                    $pageImages = $pagesRepo->saveImage($inputImageData);
                    $pageItemIds[] = $pageImages->id;
                }
            }
            $pagesRepo->deletePageImages($page->id, $notIn = $pageItemIds);
        }


        return redirect()->route('admin_pages_list')->with('success', 'Page Updated successfully');
    }

    public function delete(PagesDeleteRequest $request, PagesRepository $pagesRepo)
    {
        $page = $pagesRepo->get($request->id);

        if ($pagesRepo->delete($request->id)) {
            $event = auth()->user()->name . ' Deleted the Page with Title ' . $page->title;
            activity('Pages')->performedOn($page)->event($event)->withProperties(['page_id' => $page->id, 'data' => $page])->log('Page Deleted');

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }
}
