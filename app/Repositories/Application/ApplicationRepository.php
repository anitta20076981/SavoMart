<?php

namespace App\Repositories\Application;

use App\Models\Application;
use Illuminate\Database\Eloquent\Builder;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getForDatatable($data)
    {
        return Application::select(['*'])
            ->where(function (Builder $query) use ($data) {
                if ($data['status'] != '') {
                    $query->where('status', '=', $data['status']);
                }
            });
    }

    public function createApplication($input)
    {
        if ($application = Application::create($input)) {
            return $application;
        }

        return false;
    }

    public function updateApplication(array $input)
    {
        $application = Application::find($input['id']);
        unset($input['id']);

        if ($application->update($input)) {
            return $application;
        }

        return false;
    }

    public function getApplication($applicationId)
    {
        return Application::find($applicationId);
    }

    public function deleteApplication($applicationId)
    {
        return Application::find($applicationId)->delete();
    }

    public function applicationStatusUpdate($applicationId)
    {
        $application = Application::find($applicationId);
        $application->status = $application->status ? 0 : 1;
        $application->save();

        return $application;
    }

    public function searchApplication($keyword)
    {
        $application = Application::where('status', 1)->where('name', 'like', "%{$keyword}%");

        return $application->orderBy('name', 'asc')->paginate(30, ['*'], 'page', request()->get('page'));
    }

    public function getAllApplications()
    {
        return Application::where('status', 'active')->get();
    }
}
