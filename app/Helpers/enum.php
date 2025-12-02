<?php

if (!function_exists('vendorDocTypes')) {
    function vendorDocTypes($value = '')
    {
        $array = [
            'gst_certificate' => 'GST Certificate',
            'aadhar_card' => 'Aadhar Card',
            'pan_card' => 'Business PAN',
            'shop_license' => 'Shop & Establishment License',
            'fssai_certificate' => 'FSSAI Certificate',
            'trade_license' => 'Trade License',
            'measurement_certificate' => 'Weight & Measurement Certificate',
            'ca_cheque' => 'Current Account Cheque',
            'udhayam_certificate' => 'Udhayam Registration Certificate',
        ];

        return $value ? $array[$value] : $array;
    }
}

if (!function_exists('businessTypes')) {
    function businessTypes($value = '')
    {
        $array = [
            'manufacturer' => 'Manufacturer',
            'supplier' => 'Supplier',
            'trader' => 'Trader',
            'wholesaler' => 'Wholesaler',
            'business_service' => 'Business Service',
            'retailer' => 'Retailer',
        ];

        return $value ? $array[$value] : $array;
    }
}

if (!function_exists('attributeInputType')) {
    function attributeInputtype($value = '')
    {
        $array = [
            'dropdown' => 'Dropdown',
            'textswatch' => 'Text Swatch',
            'visualswatch' => 'Visual Swatch',
            'textfield' => 'Text Field',
            'textarea' => 'Textarea Field',
            'texteditor' => 'Text Editor',
            'date' => 'Date',
            'datetime' => 'Date Time',
            'yesno' => 'Yes/No',
            'price' => 'Price',
        ];

        return $value ? $array[$value] : $array;
    }
}

if (!function_exists('quoteStatus')) {
    function quoteStatus()
    {
        return [
            'accepted' => 'Accepted',
            'default' => 'Pending',
            'rejected' => 'Rejected',
        ];
    }
}

if (!function_exists('documentTypes')) {
    function documentTypes()
    {
        return [
            'trademark_certificate' => 'Trademark Certificate',
            'brand_authorization_letter' => 'Brand Authorization Letter',
            'invoice' => 'Invoice',
            'other_document' => 'Other Document',
        ];
    }
}

if (!function_exists('productTypes')) {
    function productTypes($value = '')
    {
        $array = [
            'configurable_product' => 'Configurable Product',
            'virtual_product' => 'Virtual Product',
            'simple_product' => 'Simple Product',
            'grouped_product' => 'Grouped Product',
            'bundle_product' => 'Bundle Product',
            'downloadable_product' => 'Downloadable Product',
        ];

        return $value ? $array[$value] : $array;
    }
}

if (!function_exists('orderStatus')) {
    function orderStatus()
    {
        return [
            'placed' => 'Placed',
            'processing' => 'Processing',
            'dispatched' => 'Dispatched',
            'delivered' => 'Delivered',
            'canceled' => 'Canceled',
            'returned' => 'Returnred',
        ];
    }
}

if (!function_exists('productStatus')) {
    function productStatus()
    {
        return [
            'suspend' => 'Suspend',
            'draft' => 'Draft',
            'publish' => 'Publish',
        ];
    }
}

if (!function_exists('produtDiscountTypes')) {
    function produtDiscountTypes()
    {
        return [
            'fixed_price' => 'Fixed Price',
            'percentage' => 'Percentage',
            'no_discount' => 'No Discount',
        ];
    }
}

if (!function_exists('quoteRequestStatus')) {
    function quoteRequestStatus()
    {
        return [
            'default' => 'Default',
            'pending' => 'Pending',
            'processing' => 'Processing',
            'order_created' => 'Order Created',
            'canceled' => 'Canceled',
        ];
    }
}

if (!function_exists('quoteStatus')) {
    function quoteStatus()
    {
        return [
            'default' => 'Default',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
        ];
    }
}
