<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\fee_category;
use App\Models\fee_type;
use App\Models\financialTrans;
use App\Models\financialTransDetails;
use App\Models\ExportModel;
use App\Models\FeeCollectionType;
use Illuminate\Pagination\Paginator;
use App\Models\CommonFeeCollection;
use App\Models\CommonFeeCollectionHeadwise;

class dummy extends Controller
{
    public function convertPage()
    {
        return view('dummy.input');
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
                    return redirect("/dummy/viewfiles");
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
        return view("dummy.filetable", compact("data"));
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
        $this->storeCommonFee($newD);
        $this->storeCommonFeeHeadWise($newD);
        $input = array(
            "is_import" => true,
        );
        ExportModel::where("id", "=", $fileid)->update($input);
        return redirect("/dummy/view");
    }
    public function storeBranch($data)
    {
        $fdata = financialTransDetails::get();
        $nData = array_slice($data, count($fdata), count($data));
        foreach ($nData as $res) {
            // print_r($res[11]);
            Branch::firstOrCreate(['branch_name' => $res[11]]);
            $dim = Branch::where("branch_name", "=", $res[11])->get()->first();
            fee_category::firstOrCreate(['fee_category' => $res[10], "branch_id" => $dim->id]);
            $br = Branch::where("branch_name", "=", $res[11])->get()->first();
            $dim2 = fee_category::where("branch_id", "=", $br->id)->where("fee_category", "=", $res[10])->get()->first();
            $collection = $this->getCollectionType($res[16]);
            fee_type::firstOrCreate(['fee_category_id' => $dim2->id, "branch_id" => $dim2->branch_id, "fee_type" => $res[16], "collection_type" => $collection]);
            $br3 = Branch::where("branch_name", "=", $res[11])->get()->first();
            $dim3 = fee_category::where("branch_id", "=", $br3->id)->where("fee_category", "=", $res[10])->get()->first();
            $fee = fee_type::where('fee_category_id', "=", $dim3->id)->where("branch_id", "=", $dim3->branch_id)->get()->first();

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
            // $br = Branch::where("branch_name", "=", $res[11])->get()->first();
            $dim = fee_category::where("branch_id", "=", $br->id)->where("fee_category", "=", $res[10])->get()->first();
            $fee = fee_type::where('fee_category_id', "=", $dim->id)->where("branch_id", "=", $dim->branch_id)->get()->first();
            $collection2 = 1;
            if (strpos(strtolower($res[16]), 'fine') !== false) {
                $collection2 = 11;
            } elseif (strpos(strtolower($res[16]), 'mess') !== false) {

                $collection2 = 2;
            } else {

                $collection2 = 1;
            }
            $input2 = array(

                "module_id" => $collection2,
                "trans_id" => $res[6],
                "admno" => $res[8],
                "rollno" => $res[7],
                "amount" => 0,
                "brid" => $fee->branch_id,
                "academicyear" => $res[3],
                "financialyear" => $res[3],
                "displayreceiptno" => $res[15],
                "collection_totalpaidamount" => 0,
            );
            CommonFeeCollection::firstOrCreate($input2);



            $FD = financialTrans::where('trans_admno', "=", $res[8])->get()->first();
            $mode = "D";
            if ($res[17] != "0") {
                $mode = "D";
            } elseif ($res[22] != "0") {
                $mode = "C";
            } elseif ($res[20] != "0") {
                $mode = "C";
            } elseif ($res[21] != "0") {
                $mode = "D";
            }
            $input3 = array(
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
                "entry_mode" => $mode,
            );
            financialTransDetails::insert($input3);


            $CF = CommonFeeCollection::where('admno', "=", $res[8])->get()->first();

            $input4 = array(
                "commonfee_id" => $CF->id,
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
                "entry_mode" => $mode,
            );
            CommonFeeCollectionHeadwise::insert($input4);
        }
    }
    public function storeFeeCategory($data)
    {
    }
    public function storeFeeTypes($data)
    {
    }
    public function getCollectionType($data)
    {
        if (strpos(strtolower($data), 'fine') !== false) {
            $dim = FeeCollectionType::where("collection_type", "=", "Academic Misc")->get();
            if (count($dim) > 0) {
                return $dim->first()->id;
            }
            return "fine";
        } elseif (strpos(strtolower($data), 'mess') !== false) {
            $dim = FeeCollectionType::where("collection_type", "=", "Hostel")->get();
            if (count($dim) > 0) {
                return $dim->first()->id;
            }
            return "mess";
        } else {
            $dim = FeeCollectionType::where("collection_type", "=", "Academic")->get();
            if (count($dim) > 0) {
                return $dim->first()->id;
            }
            return "Academic";
        }
    }
    public function storeFinancialTrans($data)
    {
    }
    public function storeFinancialTransDetails($data)
    {

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
    public function storeCommonFee($data)
    {
    }
    public function storeCommonFeeHeadWise($data)
    {


        $CF = CommonFeeCollection::get();
        foreach ($CF as $res) {
            $count = 0;
            $cfc = CommonFeeCollectionHeadwise::where("commonfee_id", "=", $res->id)->get();
            foreach ($cfc as $ress) {
                $count += (float)$ress->detail_paid_amount;
            }
            $input = array(
                "collection_totalpaidamount" => $count,
            );
            CommonFeeCollection::where("id", "=", $res->id)->update($input);
        }
    }
    public function UpdateValue(){
        $this->storeFinancialTransDetails(array());
        $this->storeCommonFeeHeadWise(array());
    }
    public function viewTable()
    {
        Paginator::useBootstrap();
        $data = financialTransDetails::paginate(10);

        return view("dummy.view", compact("data"));
    }
}
