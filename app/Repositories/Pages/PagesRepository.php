<?php

namespace App\Repositories\Pages;

use App\Models\Page;
use App\Models\PageImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class PagesRepository implements PagesRepositoryInterface
{
    public function save(array $input)
    {
        if ($page = Page::create($input)) {
            return $page;
        }

        return false;
    }

    public function getPages($data)
    {
        return Page::select(app(Page::class)->getTable() . '.*')
            ->where('slug', '<>', 'faq')
            ->when((isset($data['category_id']) && $data['category_id']), function ($query) use ($data) {
                return $query->where('category_id', $data['category_id']);
            })
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status'])) {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function get($id)
    {
        return Page::find($id);
    }

    public function update($data)
    {
        $page = Page::find($data['id']);

        if ($page->update($data)) {
            return $page;
        }

        return false;
    }

    public function delete($id)
    {
        $page = Page::find($id);

        return $page->delete();
    }

    public function getFaq($data)
    {
        return Page::select(app(Page::class)->getTable() . '.*')
            ->where('category_id', '<>', null)
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status'])) {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function getPageBySlug($slug)
    {
        return Page::where('slug', $slug)->first();
    }

    public function saveImage(array $input)
    {
        if (isset($input['id']) && $input['id']) {
            $images = PageImage::find($input['id']);
            $images->update($input);

            return $images;
        } elseif ($images = PageImage::create($input)) {
            return $images;
        }

        return false;
    }

    public function deletePageImages($pageId, $notInIds)
    {
        $items = PageImage::whereNotIn('id', $notInIds)->where('page_id', $pageId)->get();

        foreach ($items as $item) {
            if (Storage::disk('foodovity')->delete($item->file)) {
                $item->delete();
            }
        }
    }

    public function getPageImage($id)
    {
        return PageImage::find($id);
    }

    public function deleteImage($id, $fileName)
    {
        PageImage::where('id', $id)->where('file', $fileName)->delete();
    }

    public function updatePageImages(array $input)
    {
        $items = PageImage::find($input['id']);
        unset($input['id']);

        if ($items->update($input)) {
            return $items;
        }

        return false;
    }
}
