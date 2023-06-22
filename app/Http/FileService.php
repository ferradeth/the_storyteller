<?php

namespace App\Http;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public static function upload($file, $dir, $default = "default.jpg"){
        if ($file != null){
            $path = $file->store($dir, 'public');
        }
        else{
            $path = $default;
        }
        return url("/storage/".$path);
    }

    public static function uploadNew($file, $dir, $default = "default.jpg"){
        if ($file != null){
            $path = $file->move('storage'.$dir, $file->getClientOriginalName());
        }
        else{
            $path = 'storage/'.$default;
        }
//        dd($path->getPathName());
        return url($path);
    }

    public static function delete($file, $dir){
//        dd($_SERVER['DOCUMENT_ROOT'].$file);
        // $dir - ПЕРЕМЕННАЯ С ПУТЁМ ДО КАРТИНКИ ТИПА /avatars/
        $path = '/public'.$dir.pathinfo($file, PATHINFO_BASENAME) ;
        if(Storage::exists($path)){
            return Storage::delete($path);
        }
        return false;
    }

    public static function update($old_file, $dir, $new_file=""){
        $old_basename = pathinfo($old_file, PATHINFO_BASENAME);
        if ($new_file!= ""){
            $old_basename!='default.jpg'? FileService::delete($old_file, $dir): '';
            return FileService::upload($new_file, $dir);
        }
        return false;
    }
}
