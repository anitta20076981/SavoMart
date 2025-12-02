<?php

namespace App\DataTransfer;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ProductsExcelExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('productAttributes', 'ProductAttributeSet')->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Sku',
            'Name',
            'Type',
            'Price',
            'Attribute Set',
            'Description',
            'Status',
            'Stock Status',
            'Special Price',
            'Special Price To',
            'Special Price From',
            'Discount Percentage',
            'Discount Amount',
            'Attributes',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->name,
            $this->handleProductType($product),
            $product->price,
            $this->handleProductAttributeSet($product),
            strip_tags($product->description), 
            $product->status,
            $product->stock_status,
            $product->special_price,
            $product->special_price_to,
            $product->special_price_from,
            $product->discount_percentage,
            $product->discount_amount,
            $this->handleProductAttributes($product)
        ];
    }


    /**
     * Handle Product Type
     *
     * @param [type] $product
     * @return $productType
     */
    private function handleProductType($product)
    {
        switch ($product->type) {
            case 'configurable_product':
                $productType = 'Configurable Product';
                break;

            case 'virtual_product':
                $productType = 'Virtual Product';
                break;
            case 'simple_product':
                $productType = 'Simple Product';
                break;
        }

        return $productType;
    }

    /**
     * Handle Product AttributeSet
     *
     * @param [type] $product
     * @return productAttributeSet
     */
    private function handleProductAttributeSet($product)
    {
        if ( isset($product->ProductAttributeSet) && isset($product) ) {
            return $productAttributeSet = $product->ProductAttributeSet->name;
        }else {
            return '';
        }
    }

    /**
     * Handle Product Attributes
     *
     * @param [type] $product
     * @return void
     */
    private function handleProductAttributes($product)
    {
        if ( isset($product->productAttributes) && count($product->productAttributes) && isset($product) ) {

            $appendedAttributevalues = '';
            $appendedAttributeName = '';

            foreach ($product->productAttributes as $productAttributes ) {
                $appendedAttributevalues = $productAttributes->value . ',';
                $appendedAttributeName .= $productAttributes->attribute->name . ':' .$appendedAttributevalues ;
            }

            return $appendedAttributeName;

        }else{
            return $appendedAttributeName = '';
        }
    }

}
