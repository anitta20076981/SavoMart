<?php

namespace App\Repositories\Pages;

interface PagesRepositoryInterface
{
    public function save(array $input);

    public function getPages($data);

    public function get($id);

    public function update($data);

    public function delete($id);

    public function getFaq($data);

    public function getPageBySlug($slug);

    public function saveImage(array $input);
}
