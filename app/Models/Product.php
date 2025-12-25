<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
//use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    //use Searchable;

    // public function toSearchableArray()
    // {
    //     $searchableArray = [
    //         'id' => (int) $this->id,
    //         'name' => $this->name,
    //         'attributes' => $this->productAttributes->map(function ($attribute) {
    //             return [
    //                 'value' => $this->name . ' ' . $attribute['value']
    //             ];
    //         }),
    //     ];

    //     $searchableArray['category'] = $this->categoryDeatails->map(function ($item) {
    //         $item['parent'] = $item->parentCategory;

    //         return $item->only(['id', 'name',  'parent']);
    //     });

    //     return $searchableArray;
    // }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            foreach ($model->productImages as $item) {
                if (Storage::disk('savomart')->delete($item->image_path)) {
                    $item->delete();
                }
            }

            if ($model->productThumbnail && Storage::disk('savomart')->delete($model->thumbnail)) {
                $model->productThumbnail()->delete();
            }

            if ($model->productAttributes) {
                $model->productAttributes()->delete();
            }

            if ($model->categories) {
                $model->categories()->delete();
            }


        });
    }

    protected $table = 'products';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $appends = ['thumbnail', 'thumbnail_url', 'final_price'];

    protected $fillable = [
        'special_price',
        'special_price_from',
        'special_price_to',
        'attribute_set_id',
        'type',
        'quantity',
        'stock_status',
        'sku',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'short_description',
        'price',
        'discount_id',
        'discount_amount',
        'discount_percentage',
        'status',
        'parent_id',
        'delivery_expected_time'
    ];

    protected function getProductFinalPriceAttribute()
    {
        $discount = $productMinPrice = 0;
        $now = Carbon::now();

        $specialPrice = $this->special_price;

        // $formattedDateFrom = Carbon::parse($this->special_price_from)->format(config('date_format.date_time_display'));
        // $formattedDateTo = Carbon::parse($this->special_price_to)->format(config('date_format.date_time_display'));

        $formattedDateFrom =  ($this->special_price_from != null) ? Carbon::createFromFormat('Y-m-d H:i:s', $this->special_price_from): '';
        $formattedDateTo =  ($this->special_price_to != null) ? Carbon::createFromFormat('Y-m-d H:i:s', $this->special_price_to):'';

        $specialPriceFrom = $this->special_price_from ? $formattedDateFrom : null;

        $specialPriceTo = $this->special_price_to ? $formattedDateTo : null;

        if ($specialPrice > 0 && $now >= $specialPriceFrom && $now <= $specialPriceTo) {
            $productMinPrice = $specialPrice ? $specialPrice : $this->price;
        }
        else {
            $catalogRuleProducts = $this->catalogRuleProducts;
            $productMinPrice = $this->price;
        }
        $productMinPrice = ($productMinPrice < 0) ? 0 : $productMinPrice;

        return $productMinPrice;
    }

    protected function getFinalPriceAttribute()
    {
        $productMinPrice = $this->product_final_price;
        $commission = $this->product_commision;

        return number_format($productMinPrice + $commission, 2, '.', '');
    }

    public function ProductAttributeSet()
    {
        return $this->hasOne(AttributeSet::class, 'id', 'attribute_set_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id')->with('attribute');
    }


    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->where('image_role', 'BASE');
    }

    public function productThumbnail()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->where('image_role', 'THUMBNAIL');
    }

    public function getThumbnailAttribute()
    {
        return $this->productThumbnail && $this->productThumbnail->image_path ? $this->productThumbnail->image_path : '';
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->productThumbnail && $this->productThumbnail->image_path ? Storage::disk('savomart')->url($this->productThumbnail->image_path) : '';
    }

    public function categories()
    {
        return $this->hasMany(ProductCategories::class, 'product_id', 'id');
    }

    public function variations()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function configuredAttributeValues()
    {
        return $this->hasMany(ProductConfigureAttributeValue::class, 'product_id', 'id');
    }



    public function productRelations()
    {
        return $this->hasManyThrough(Product::class, ProductRelation::class, 'product_id', 'id', 'id', 'related_product_id');
    }

    public function categoryDeatails()
    {
        return $this->hasManyThrough(Category::class, ProductCategories::class, 'product_id', 'id', 'id', 'category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function cartItem()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'id');
    }

    public function getCanDeleteAttribute()
    {
        if (
            $this->orderItems()->count()
            || $this->cartItem()->count()
        ) {
            return false;
        }

        return true;
    }
}
