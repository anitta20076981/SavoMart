<?php

namespace App\Repositories\Application;

interface ApplicationRepositoryInterface
{
    public function getForDatatable($data);

    public function createApplication($input);

    public function updateApplication(array $input);

    public function getApplication($applicationId);

    public function deleteApplication($applicationId);

    public function applicationStatusUpdate($applicationId);

    public function searchApplication($keyword);

    public function getAllApplications();
}
