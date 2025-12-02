<?php

namespace App\Repositories\Contents;

interface ContentsRepositoryInterface
{
    public function save(array $input);

    public function getContents($data);

    public function get($id);

    public function update($data);

    public function delete($id);

    public function searchContentCategory($term, $not);
}
