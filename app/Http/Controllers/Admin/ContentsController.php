<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Contents\ContentsAddRequest;
use App\Http\Requests\Admin\Contents\ContentsCreateRequest;
use App\Http\Requests\Admin\Contents\ContentsDeleteRequest;
use App\Http\Requests\Admin\Contents\ContentsListDataRequest;
use App\Http\Requests\Admin\Contents\ContentsListRequest;
use App\Http\Requests\Admin\Contents\ContentsUpdateRequest;
use App\Repositories\Contents\ContentsRepositoryInterface as ContentsRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ContentsController extends Controller
{
    public function list(ContentsListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Contents'],
        ];

        return view('admin.contents.listContents', compact('breadcrumbs'));
    }

    public function table(ContentsListDataRequest $request, ContentsRepository $contentsRepo)
    {
        $contents = $contentsRepo->getContents($request->all());
        $dataTableJSON = DataTables::of($contents)
            ->addIndexColumn()
            ->editColumn('name', function ($content) {
                $data['url'] = request()->user()->can('contents_view') ? route('admin_contents_edit', ['id' => $content->id]) : '';
                $data['text'] = $content->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('status', function ($content) {
                return view('admin.elements.listStatus')->with('data', $content);
            })
            ->addColumn('action', function ($content) use ($request) {
                $data['edit_url'] = request()->user()->can('contents_update') ? route('admin_contents_edit', ['id' => $content->id]) : '';
                $data['delete_url'] = request()->user()->can('contents_delete') && $content->is_deletable == 1 ? route('admin_contents_delete', ['id' => $content->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function add(ContentsAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_contents_list', 'name' => 'Contents', 'permission' => 'contents_read'],
            ['name' => 'Add Content'],
        ];

        return view('admin.contents.addContents', compact('breadcrumbs'));
    }

    public function save(ContentsCreateRequest $request, ContentsRepository $contentsRepo)
    {
        $data = [];
        $slug = $request->slug != null ? $request->slug : Str::slug($request->title);
        $data = [
            'content_category_id' => $request->content_category_id ? $request->content_category_id : 0,
            'name' => $request->name,
            'title' => $request->title,
            'status' => $request->status,
            'slug' => $slug,
            'content' => $request->content,
            'is_deltetable' => 1,
        ];

        if ($request->hasFile('file')) {
            $filePath = 'contents/file';
            $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('file'));
            $data['file'] = $fileName;
        }

        if ($request->hasFile('thumbnail')) {
            $filePath = 'contents/thumbnail';
            $data['thumbnail'] = Storage::disk('savomart')->putFile($filePath, $request->file('thumbnail'));
        } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
            $data['thumbnail'] = '';
        }
        $content = $contentsRepo->save($data);
        $event = auth()->user()->name . ' Added the Content with Title ' . $request->title;

        return redirect()->route('admin_contents_list')->with('success', 'Content added successfully');
    }

    public function edit(ContentsAddRequest $request, ContentsRepository $contentsRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_contents_list', 'name' => 'Contents', 'permission' => 'contents_update'],
            ['name' => 'Edit Content'],
        ];
        $contents = $contentsRepo->get($request->id);
        $old = []; 

        if ($contents->content_category_id) {
            if (old('content_category_id', $contents->category->id)) {
                $old['content_category_id'] = $contentsRepo->getCategory(old('content_category_id', $contents->category->id));
            }
        }

        return view('admin.contents.editContents', compact('breadcrumbs', 'contents', 'old'));
    }

    public function update(ContentsUpdateRequest $request, ContentsRepository $contentsRepo)
    {
        $currentData = $contentsRepo->get($request->id);
        $slug = $request->slug != null ? $request->slug : $currentData->slug;
        $data = [
            'id' => $request->id,
            'content_category_id' => $request->content_category_id ? $request->content_category_id : 0,
            'name' => $request->name,
            'title' => $request->title,
            'status' => $request->status,
            'slug' => $slug,
            'content' => $request->content,
        ];

        if ($request->hasFile('file')) {
            $filePath = 'contents/file';
            $fileName = Storage::disk('savomart')->putFile($filePath, $request->file('file'));
            $data['file'] = $fileName;
        } elseif ($request->has('file_remove') && $request->file_remove) {
            $data['file'] = '';
        }

        if ($request->hasFile('thumbnail')) {
            $filePath = 'contents/thumbnail';
            $data['thumbnail'] = Storage::disk('savomart')->putFile($filePath, $request->file('thumbnail'));
        } elseif ($request->has('thumbnail_remove') && $request->thumbnail_remove) {
            $data['thumbnail'] = '';
        }
        $content = $contentsRepo->update($data);
        $event = auth()->user()->name . ' Updated the Content with Title ' . $currentData->title;

        return redirect()->route('admin_contents_list')->with('success', 'Content Updated successfully');
    }

    public function delete(ContentsDeleteRequest $request, ContentsRepository $contentsRepo)
    {
        $content = $contentsRepo->get($request->id);

        if ($contentsRepo->delete($request->id)) {
            $event = auth()->user()->name . ' Deleted the Content with Title ' . $content->title;

            return response()->json(['status' => 1, 'message' => 'Content deleted successfully']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }
}