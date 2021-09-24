<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\fee_category;
use App\Models\fee_type;
use App\Models\financialTrans;
use App\Models\financialTransDetails;
use App\Models\ExportModel;
use Illuminate\Pagination\Paginator;

class CSVController extends Controller
{
    public function convertPage()
    {
        return view('input');
    }
    public function getCsv(Request $req)
    {
        ini_set('post_max_size', '2000M');
        ini_set('upload_max_filesize', '2000M');
        ini_set('max_execution_time', '180000');
        ini_set('memory_limit', '-1');
        $file = $req->file('file');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        $valid_extension = array("csv");
        $maxFileSize = 20971520000;


        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
                $location = 'uploads';
                $file->move($location, $filename);
                $input = array(
                    "file_name" => $filename,
                    "is_import" => false,
                );
                if (ExportModel::insert($input)) {
                    return redirect("/viewfiles");
                } else {
                    return redirect()->back();
                }
            } else {
                echo "max size";
            }
        } else {
        }
    }
    public function getImportFiles()
    {
        Paginator::useBootstrap();
        $data = ExportModel::where("is_import", "=", false)->paginate(10);
        return view("filetable", compact("data"));
    }
    public function importData(Request $req, $fileid)
    {
        ini_set('post_max_size', '2000M');
        ini_set('upload_max_filesize', '2000M');
        ini_set('max_execution_time', '180000');
        ini_set('memory_limit', '-1');

        $location = 'uploads';
        $filename = ExportModel::where("id", "=", $fileid)->get()->first();
        $filepath = public_path($location . "/" . $filename->file_name);
        $file = fopen($filepath, "r");
        $importData_arr = array();
        $i = 0;
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            $num = count($filedata);
            for ($c = 0; $c < $num; $c++) {

                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file);

        $newD =  array_slice($importData_arr, 5, count($importData_arr));
        $data = array();

        foreach ($newD as $row) {
            array_push($data, explode("\t", $row[0]));
        }
        // print_r($newD[0]);
        // return $newD;
        // return fee_category::with("getBranch")->get();
        $this->storeBranch($newD);
        $this->storeFeeCategory($newD);
        $this->storeFeeTypes($newD);
        $this->storeFinancialTrans($newD);
        $this->storeFinancialTransDetails($newD);
        $input = array(
            "is_import" => true,
        );
        ExportModel::where("id", "=", $fileid)->update($input);
        return redirect("/view");
    }
    public function storeBranch($data)
    {
        $nData = array_slice($data, 1, count($data));
        foreach ($nData as $res) {
            // print_r($res[11]);
            Branch::firstOrCreate(['branch_name' => $res[11]]);
        }
    }
    public function storeFeeCategory($data)
    {
        $nData = array_slice($data, 1, count($data));
        foreach ($nData as $res) {
            $dim = Branch::where("branch_name", "=", $res[11])->get()->first();
            fee_category::firstOrCreate(['fee_category' => $res[10], "branch_id" => $dim->id]);
        }
    }
    public function storeFeeTypes($data)
    {
        $nData = array_slice($data, 1, count($data));
        foreach ($nData as $res) {
            $br = Branch::where("branch_name", "=", $res[11])->get()->first();
            $dim = fee_category::where("branch_id", "=", $br->id)->where("fee_category", "=", $res[10])->get()->first();
            fee_type::firstOrCreate(['fee_category_id' => $dim->id, "branch_id" => $dim->branch_id, "fee_type" => $res[16]]);
        }
    }
    public function storeFinancialTrans($data)
    {
        $nData = array_slice($data, 1, count($data));
        foreach ($nData as $res) {
            $br = Branch::where("branch_name", "=", $res[11])->get()->first();
            $dim = fee_category::where("branch_id", "=", $br->id)->where("fee_category", "=", $res[10])->get()->first();
            $fee = fee_type::where('fee_category_id', "=", $dim->id)->where("branch_id", "=", $dim->branch_id)->get()->first();
            $input = array(
                'fee_category_id' => $fee->id,
                "branch_id" => $fee->branch_id,
                "fee_type_id" => $fee->id,
                'trans_date' => $res[1],
                'trans_session' => $res[3],
                'trans_rollno' => $res[7],
                'trans_admno' => $res[8],
                'trans_receipt' => $res[15],
                'trans_totalpaidamount' => 0,
            );
            financialTrans::firstOrCreate($input);
        }
    }
    public function storeFinancialTransDetails($data)
    {
        $nData = array_slice($data, 1, count($data));
        foreach ($nData as $res) {
            $FD = financialTrans::where('trans_admno', "=", $res[8])->get()->first();
            $input = array(
                "financial_trans_id" => $FD->id,
                "detail_admno" => $res[8],
                "detail_due_amount" => $res[17],
                "detail_paid_amount" => $res[18],
                "detail_concession_amount" => $res[19],
                "detail_scholarship_amount" => $res[20],
                "detail_reverse_concession_amount" => $res[21],
                "detail_write_offamount" => $res[22],
                "detail_adjusted_amount" => $res[23],
                "detail_refund_amount" => $res[24],
                "detail_fund_tranCfer_amount" => $res[25],
                "detail_remark" => $res[26],
            );
            financialTransDetails::insert($input);
        }
        $FD = financialTrans::get();
        foreach ($FD as $res) {
            $count = 0;
            $fdd = financialTransDetails::where("financial_trans_id", "=", $res->id)->get();
            foreach ($fdd as $ress) {
                $count += (float)$ress->detail_paid_amount;
            }
            $input = array(
                "trans_totalpaidamount" => $count,
            );
            financialTrans::where("id", "=", $res->id)->update($input);
        }
    }
    public function viewTable()
    {
        Paginator::useBootstrap();
        $data = financialTransDetails::paginate(10);

        return view("view", compact("data"));
    }
}
