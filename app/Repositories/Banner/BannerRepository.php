<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Models\BannerItem;
use App\Models\BannerSection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BannerRepository implements BannerRepositoryInterface
{
    public function getForDatatable($data)
    {
        $banner = Banner::select(['id', 'name', 'is_deletable', 'status'])
            ->where(function (Builder $query) use ($data) {
                // if ($data['status'] != '') {
                //     $query->where('status', '=', $data['status']);
                // }
            })->orderBy('id', 'DESC');

        return $banner;
    }

    public function createBanner($input)
    {
        if ($banner = Banner::create($input)) {
            return $banner;
        }

        return false;
    }

    public function updateBanner(array $input)
    {
        $banner = Banner::find($input['id']);
        unset($input['id']);

        if ($banner->update($input)) {
            return $banner;
        }

        return false;
    }

    public function updateBannerItems(array $input)
    {
        $bannerItem = BannerItem::find($input['id']);
        unset($input['id']);

        if ($bannerItem->update($input)) {
            return $bannerItem;
        }

        return false;
    }

    public function updateSlug(array $input)
    {
        $banner = Banner::find($input['id']);
        $banner->slug = $input['slug'];
        $banner->save();

        return $banner;
    }

    public function getBanner($bannerId)
    {
        return Banner::with(['items'])->findOrFail($bannerId);
    }

    public function getAllBanner()
    {
        return Banner::all();
    }

    public function deleteBanner($bannerId)
    {
        $banner = Banner::with(['items'])->findOrFail($bannerId);
        $banner->items()->delete();

        return $banner->delete();
    }

    public function saveImage(array $input)
    {
        if (isset($input['id']) && $input['id']) {
            $bannerItem = BannerItem::find($input['id']);
            $bannerItem->update($input);

            return $bannerItem;
        } elseif ($bannerItem = BannerItem::create($input)) {
            return $bannerItem;
        }

        return false;
    }

    public function deleteBannerItems($bannerId, $notInIds)
    {
        $items = BannerItem::whereNotIn('id', $notInIds)->where('banner_id', $bannerId)->get();

        foreach ($items as $item) {
            if (Storage::disk('savomart')->delete($item->file)) {
                $item->delete();
            }
        }
    }

    public function getBannerItem($id)
    {
        return BannerItem::find($id);
    }

    public function deleteImage($id, $fileName)
    {
        BannerItem::where('id', $id)->where('file', $fileName)->delete();
    }

    public function saveFile(array $input)
    {
        if ($bannerItem = BannerItem::create($input)) {
            return $bannerItem;
        }

        return false;
    }

    public function getBannerSection($sectionId)
    {
        return BannerSection::find($sectionId);
    }

    public function searchBannerSection($keyword)
    {
        return BannerSection::where('name', 'like', "%{$keyword}%")->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getBannerByslug($slug)
    {
        $banner = Banner::where('slug', $slug)->first();
        return BannerItem::where('banner_id',  $banner->id)->get();
    }
}