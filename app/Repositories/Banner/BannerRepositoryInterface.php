<?php

namespace App\Repositories\Banner;

interface BannerRepositoryInterface
{
    public function getForDatatable($data);

    public function createBanner($details);

    public function getAllBanner();

    public function deleteBanner($bannerId);

    public function getBanner($bannerId);

    public function saveImage(array $input);

    public function deleteBannerItems($bannerId, $notInIds);

    public function getBannerItem($id);

    public function deleteImage($id, $fileName);

    public function updateBanner(array $input);

    public function updateBannerItems(array $input);

    public function updateSlug(array $input);

    public function saveFile(array $input);

    public function getBannerSection($sectionId);

    public function searchBannerSection($keyword);

    public function getBannerByslug($slug);
}
