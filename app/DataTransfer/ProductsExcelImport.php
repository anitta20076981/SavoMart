<?php

namespace App\DataTransfer;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductsExcelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $product = Product::updateOrCreate(['sku' => $row['sku']], [
            'name'                  => $row['name'],
            'price'                 => $row['price'],
            'status'                => $row['status'],
            'special_price'         => $row['special_price'] ? $row['special_price'] : 0 ,
            'special_price_to'      => $row['special_price_to'],
            'special_price_from'    => $row['special_price_from'],
            'discount_percentage'   => $row['discount_percentage'],
            'discount_amount'       => $row['discount_amount'],
            'attribute_set_id'      => array_key_exists('attribute_set_id', $row) ? $row['attribute_set_id'] : 1 
        ]);

        return $product;


    }

}