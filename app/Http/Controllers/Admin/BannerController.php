<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Includes\Banner\Image;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerAddRequest;
use App\Http\Requests\Admin\Banner\BannerCreateRequest;
use App\Http\Requests\Admin\Banner\BannerDeleteRequest;
use App\Http\Requests\Admin\Banner\BannerEditRequest;
use App\Http\Requests\Admin\Banner\BannerListDataRequest;
use App\Http\Requests\Admin\Banner\BannerListRequest;
use App\Http\Requests\Admin\Banner\BannerUpdateRequest;
use App\Repositories\Banner\BannerRepositoryInterface as BannerRepository;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{
    use Image;

    public function listBanner(BannerListRequest $request,BannerRepository $bannerRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Banner'],
        ];
        $banners = $bannerRepo->getForDatatable($request->all())->get();

        return view('admin.banner.listBanner', compact('breadcrumbs','banners'));
    }

    public function bannerListData(BannerListDataRequest $request, BannerRepository $bannerRepo)
    {
        $banner = $bannerRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($banner)
            ->addIndexColumn()
            ->editColumn('name', function ($banner) {
                $data['url'] = request()->user()->can('banner_view') ? route('admin_banner_edit', ['id' => $banner->id]) : '';
                $data['text'] = $banner->name;

                return view('admin.elements.listLink', compact('data'));
            })
            ->addColumn('status', function ($banner) {
                return view('admin.elements.listStatus')->with('data', $banner);
            })
            ->addColumn('action', function ($banner) use ($request) {
                $data['edit_url'] = request()->user()->can('banner_update') ? route('admin_banner_edit', ['id' => $banner->id]) : '';
                $data['delete_url'] = (request()->user()->can('banner_delete') && ($banner->is_deletable == 1)) ? route('admin_banner_banner_delete', ['id' => $banner->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function addBanner(
        BannerAddRequest $request,
        BannerRepository $bannerRepo
    ) {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_banner_list', 'name' => 'Banner', 'permission' => 'banner_read'],
            ['name' => 'Add Banner'],
        ];

        return view('admin.banner.addBanner', compact('breadcrumbs'));
    }

    public function createBanner(BannerCreateRequest $request, BannerRepository $bannerRepo)
    {
        $inputData = [
            'status' => $request->status,
            'name' => $request->name,
            'banner_section_id' => $request->banner_section_id,
        ];
        $inputData['multiple'] = ($request->has('images') && count($request->images) == 1) ? 0 : 1;

        $banner = $bannerRepo->createBanner($inputData);

        if ($request->has('images')) {
            foreach ($request->images as $id => $file) {
                $inputImageData = [
                    'id' => $id,
                    'banner_id' => $banner->id,
                    'file' => $file,
                ];
                $bannerRepo->saveImage($inputImageData);
            }
        }

        $event = auth()->user()->name . ' Added banner with name ' . $request->name;
        
        return redirect()
            ->route('admin_banner_list')
            ->with('success', 'Banner added successfully');
    }

    public function editBanner(
        BannerEditRequest $request,
        BannerRepository $bannerRepo
    ) {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_banner_list', 'name' => 'Banner', 'permission' => 'banner_read'],
            ['name' => 'Banner Details'],
        ];
        $banner = $bannerRepo->getBanner($request->id);
        $bannerSection = $bannerRepo->getBannerSection(($banner->banner_section_id));

        return view('admin.banner.editBanner', compact('banner', 'breadcrumbs', 'bannerSection'));
    }

    public function updateBanner(BannerUpdateRequest $request, BannerRepository $bannerRepo)
    {
        $banner = $bannerRepo->getBanner($request->id);

        if (isset($request->images['file_name'])) {
            $isMultiple = count($request->images['file_name']) == 1 ? 0 : 1;
        } else {
            $isMultiple = 0;
        }
        $inputData = [
            'id' => $request->id,
            'status' => $request->status,
            'name' => $request->name,
            'multiple' => $isMultiple,
            'banner_section_id' => $request->banner_section_id,
        ];
        $bannerRepo->getBanner($request->id);

        $bannerRepo->updateBanner($inputData);

        if (!$banner->is_deletable) {
            $inputData = [
                'id' => $request->id,
                'slug' => $banner->slug,
            ];
            $bannerRepo->updateSlug($inputData);
        }

        if (isset($request->images) && $request->images) {
            $bannerItemIds = [];

            if ($request->has('images')) {
                foreach ($request->images as $id => $file) {
                    $inputImageData = [
                        'id' => $id,
                        'banner_id' => $banner->id,
                        'file' => $file,
                    ];
                    $bannerItem = $bannerRepo->saveImage($inputImageData);
                    $bannerItemIds[] = $bannerItem->id;
                }
            }
            $bannerRepo->deleteBannerItems($banner->id, $notIn = $bannerItemIds);
        }

        $event = auth()->user()->name . ' Update Banner';
        
        return redirect()
            ->route('admin_banner_list')
            ->with('success', 'Banner updated successfully');
    }

    public function bannerDelete(BannerRepository $bannerRepo, BannerDeleteRequest $request)
    {
        $banner = $bannerRepo->getBanner($request->id);

        if ($bannerRepo->deleteBanner($request->id)) {
            $event = auth()->user()->name . ' Deleted Banner';
            
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }
}