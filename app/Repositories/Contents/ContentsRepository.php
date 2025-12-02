<?php

namespace App\Repositories\Contents;

use App\Models\Content;
use App\Models\ContentCategory;
use Illuminate\Database\Eloquent\Builder;

class ContentsRepository implements ContentsRepositoryInterface
{
    public function save(array $input)
    {
        if ($content = Content::create($input)) {
            return $content;
        }

        return false;
    }

    public function getContents($data)
    {
        return Content::select(app(Content::class)->getTable() . '.*')->with('category')
            ->where(function (Builder $query) use ($data) {
                if (isset($data['status'])) {
                    $query->where('status', '=', $data['status']);
                }
            })->orderBy('id', 'DESC');
    }

    public function get($id)
    {
        return Content::find($id);
    }

    public function update($data)
    {
        $content = Content::find($data['id']);

        if ($content->update($data)) {
            return $content;
        }

        return false;
    }

    public function delete($id)
    {
        $content = Content::find($id);

        return $content->delete();
    }

    public function searchContentCategory($term, $not)
    {
        $category = ContentCategory::where('name', 'like', "%{$term}%");
        if ($not) {
            $category = $category->where('id', '!=', $not);
        }
        return $category->get();
    }
}