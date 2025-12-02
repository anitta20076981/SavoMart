<?php

namespace App\Repositories\OrderReturn;

interface OrderReturnRepositoryInterface
{
    public function saveReturnImages($input);

    public function create($input);

    public function getOrderReturnItemsCount($orderId);

    public function getForDatatable($data);

    public function get($returnId);

    public function getImage($id);

    public function deleteImage($id, $fileName);

    public function update(array $input);

    public function getReturnItemCount($orderId);

    public function orderReturnList($filterData);

    public function delete($orderReturnId);
}
