<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Application\ApplicationAddRequest;
use App\Http\Requests\Admin\Application\ApplicationCreateRequest;
use App\Http\Requests\Admin\Application\ApplicationDeleteRequest;
use App\Http\Requests\Admin\Application\ApplicationEditRequest;
use App\Http\Requests\Admin\Application\ApplicationListDataRequest;
use App\Http\Requests\Admin\Application\ApplicationListRequest;
use App\Http\Requests\Admin\Application\ApplicationStatusChangeRequest;
use App\Http\Requests\Admin\Application\ApplicationUpdateRequest;
use App\Repositories\Application\ApplicationRepositoryInterface as ApplicationRepository;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller
{
    public function list(ApplicationListRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['name' => 'Applications'],
        ];

        return view('admin.application.listApplications', compact('breadcrumbs'));
    }

    public function applicationListData(ApplicationListDataRequest $request, ApplicationRepository $applicationRepo)
    {
        $applications = $applicationRepo->getForDatatable($request->all());
        $dataTableJSON = DataTables::of($applications)
            ->addIndexColumn()
            ->addColumn('status', function ($application) {
                return view('admin.elements.listStatus')->with('data', $application);
            })
            ->addColumn('action', function ($application) use ($request) {
                $data['edit_url'] = request()->user()->can('application_update') ? route('admin_application_edit', ['id' => $application->id]) : '';
                $data['delete_url'] = request()->user()->can('application_delete') ? route('admin_application_delete', ['id' => $application->id]) : '';

                return view('admin.elements.listAction', compact('data'));
            })
            ->make();

        return $dataTableJSON;
    }

    public function addApplication(ApplicationAddRequest $request)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_application_list', 'name' => 'Application', 'permission' => 'application_read'],
            ['name' => 'Add Application'],
        ];

        return view('admin.application.addApplication', compact('breadcrumbs'));
    }

    public function createApplication(ApplicationCreateRequest $request, ApplicationRepository $applicationRepo)
    {
        $inputData = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1,
        ];

        if ($request->hasFile('logo')) {
            $inputData['logo'] = Storage::disk('grocery')->putFile('applications', $request->file('logo'));
        }

        $application = $applicationRepo->createApplication($inputData);
        
        return redirect()
            ->route('admin_application_list')
            ->with('success', 'Application added successfully');
    }

    public function editApplication(ApplicationEditRequest $request, ApplicationRepository $applicationRepo)
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Dashboard'],
            ['link' => 'admin_application_list', 'name' => 'Applications', 'permission' => 'application_read'],
            ['name' => 'Application Details'],
        ];
        $application = $applicationRepo->getApplication($request->id);

        return view('admin.application.editApplication', compact('application', 'breadcrumbs'));
    }

    public function updateApplication(ApplicationUpdateRequest $request, ApplicationRepository $applicationRepo)
    {
        $inputData = [
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ];

        if ($request->hasFile('logo')) {
            $fileName = Storage::disk('grocery')->putFile('applications', $request->file('logo'));
            $inputData['logo'] = $fileName;
        } elseif ($request->logo_remove == 1) {
            $inputData['logo'] = '';
        }
        $application = $applicationRepo->updateApplication($inputData);
        
        return redirect()
            ->route('admin_application_list')
            ->with('success', 'Application updated successfully');
    }

    public function statusChange(ApplicationStatusChangeRequest $request, ApplicationRepository $applicationRepo)
    {
        if ($applicationRepo->applicationStatusUpdate($request->id)) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return response()->json(['status' => 0, 'message' => 'failed']);
    }

    public function deleteApplication(ApplicationRepository $applicationRepo, ApplicationDeleteRequest $request)
    {
        $application = $applicationRepo->getApplication($request->id);
        
        if ($applicationRepo->deleteApplication($request->id)) {
            if ($request->ajax()) {
                return response()->json(['status' => 1, 'message' => 'Application deleted successfully']);
            } else {
                return redirect()->route('admin_application_list')->with('success', 'Application deleted successfully');
            }
        }

        if ($request->ajax()) {
            return response()->json(['status' => 0, 'message' => 'Failed to delete']);
        } else {
            return redirect()->route('admin_application_list')->with('success', 'Failed to delete');
        }
    }
}
