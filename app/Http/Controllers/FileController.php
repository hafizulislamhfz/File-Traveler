<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use ZipArchive;



class FileController extends Controller
{
    //function for store file
    public function store_file(Request $request){
        $files = $request->file('files');
        //Get the file size
        $totalSize = 0;
        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }
        //custom validation,check AppServiceProvider.php
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file',
            'files' => 'total_size:80', //max total size in MB
        ]);

        $totalSizeMB = 80;
        $maxTotalSizeInBytes = $totalSizeMB * 1048576;//max total size in byte
        if ($validator->fails()) {
            //check file size is valid or not
            if($totalSize > $maxTotalSizeInBytes){
                return response()->json(['is_error'=>1, 'error'=>"Total Max Size is 80 MB."]);
            }else{
                return response()->json(['is_error'=>1, 'error'=>"File required."]);
            }
        }else{
            if ($request->ajax()){
                //generate random uniqe 6 digit receive key
                $receive_key = mt_rand(100000, 999999);
                while (File::where('receive_code', $receive_key)->where('status', 1)->exists()) {
                    $receive_key = mt_rand(100000, 999999);
                }
                //if file are more than one,make it zip file
                if (count($files) > 1) {
                    //make it zip and upload to public folder
                    $zip = new \ZipArchive();
                    $folderName = 'UploadedFiles';
                    $zipFileName = 'File-Travelar-' . time() . '.zip';

                    // Create the full path for the zip file
                    $zipPath = public_path($folderName . DIRECTORY_SEPARATOR . $zipFileName);
                    $zip->open($zipPath, \ZipArchive::CREATE);

                    foreach ($files as $file) {
                        $originalName = $file->getClientOriginalName();
                        $zip->addFile($file->getRealPath(), $originalName);
                    }
                    $zip->close();
                    //file details store to database
                    File::create([
                        'name' => $zipFileName,
                        'receive_code' => $receive_key,
                    ]);
                    //response with the receive key
                    return response()->json(['receive_key' => $receive_key]);
                }else{//if file count is one
                    //upload to public folder
                    $originalName = $file->getClientOriginalName();
                    $FileName = 'File-Travelar-' . time() .'-'. $originalName;
                    $file->move(public_path('UploadedFiles'), $FileName);
                    //file details store to database
                    File::create([
                        'name' => $FileName,
                        'receive_code' => $receive_key,
                    ]);
                    //response with the receive key
                    return response()->json(['receive_key'=> $receive_key]);
                }

            }
        }
    }


    //this function is for check receive key is invalid and expire or not,and file exist or not
    //if everything ok file will dowload with ajax from share function
    public function share_file(Request $request){
        //validate the receive key
        $validator = Validator::make($request->all(), [
            'receive_key' => 'required|digits:6|numeric',
        ]);
        if ($validator->fails()){
            return response()->json(['is_error'=>1, 'error'=>"Must be a 6 digits key."]);
        }else{
            $receive_key = $request->input('receive_key');
            $file = File::where('receive_code', $receive_key)->first();
            if($file) {
                if($file->status == 1){
                    $FileName = $file->name;
                    $filePath = public_path('UploadedFiles/' . $FileName);
                    if (file_exists($filePath)) {
                        return response()->json(['fileexist'=>1, 'FileName'=>$FileName]);
                    } else {
                        return response()->json(['is_error'=>1, 'error'=>"File doesn't exist."]);
                    }
                }else{
                    return response()->json(['is_error'=>1, 'error'=>"Key Time out."]);
                }
            } else {
                return response()->json(['is_error'=>1, 'error'=>"Invalid receive key."]);
            }
        }
    }

    //this function share(return) file with receive key
    public function share($receiveKey){
        $file = File::where('receive_code', $receiveKey)->first();
        if($file){
            if($file->status == 1){
                $FileName = $file->name;
                return Response::download('UploadedFiles/'.$FileName);
            }
        }

    }
}
