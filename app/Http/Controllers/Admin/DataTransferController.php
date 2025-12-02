<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DataTransfer\HandleExportRequest;
use Illuminate\Http\Request;
use App\DataTransfer\ProductsExcelExport;
use App\DataTransfer\ProductsExcelImport;
use Maatwebsite\Excel\Facades\Excel;


use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class DataTransferController extends Controller
{

    public function index()
    {
        $breadcrumbs = [
            ['link' => 'admin_dashboard', 'name' => 'Index', 'permission' => 'data_transfer_read'],
            ['name' => 'Data Transfer'],
        ];

        return view('admin.dataTransfer.index' , compact('breadcrumbs'));
    }


    public function importIndex()
    {
        $breadcrumbs = [
            ['link' => 'admin_data_transfer_index', 'name' => 'Data Transfer'],
            ['name' => 'Import'],
        ];

        return view('admin.dataTransfer.import' , compact('breadcrumbs'));
    }

    public function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        try {
            Excel::import(new ProductsExcelImport, $file);

            return redirect()->back()->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Error occurred while importing products: ' . $e->getMessage());
        }
    }

    public function dowloadSample()
    {
        $filePath = resource_path('import/import-products-sample.xlsx');
        $fileName = 'import-products-sample.xlsx';

        return response()->download($filePath, $fileName);
    }


    /**
     * ***** Export
     *
     *
     *
     */

    public function exportIndex()
    {

        $breadcrumbs = [
            ['link' => 'admin_data_transfer_index', 'name' => 'Data Transfer'],
            ['name' => 'Export'],
        ];

        return view('admin.dataTransfer.export' , compact('breadcrumbs'));
    }

    public function handleExport(HandleExportRequest $request)
    {
        return $this->handleExportType($request);
    }

    private function handleExportType($request)
    {

        if($request->export_type == 'xlsx' )
        {
            return Excel::download(new ProductsExcelExport(), 'product.xlsx');
        }
    }

    private function storeExport($request, $excelExport){
        if($request->export_type == 'xlsx' ) {

        }else{

        }
    }


}