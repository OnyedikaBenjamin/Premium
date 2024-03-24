<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\File;
use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;
use \RecursiveDirectoryIterator;
use \DirectoryIterator;

class LanguageController extends Controller
{


    function getFile(Request $request){
        $requestLangData = $request->all();
        $filename = $requestLangData['file'];
        $requestLang = $requestLangData['lang']; 
        $langData = trans($requestLangData['file'],[],$requestLangData['lang']);
    
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($langData),RecursiveIteratorIterator::SELF_FIRST);
        $path = [];
        $flatArray = [];
        
        foreach ($iterator as $key => $value) {
            $path[$iterator->getDepth()] = $key;
        
            if (!is_array($value)) {
                $flatArray[
                    implode('|', array_slice($path, 0, $iterator->getDepth() + 1))
                ] = $value;
            }
        }
        $data  = view('language.index', compact('flatArray','filename','requestLang'))->render();
        return comman_custom_response($data);
    }

    function saveFileContent(Request $request){
        $data = $request->all();
       
        $requestFile = $data['filename'] .'.php';
        $langDir = resource_path().'/lang/';
        $filename = $langDir .$data['requestLang'] .'/' . $requestFile;
         
        unset($data['_token']);
        unset($data['filename']);
        unset($data['requestLang']);

        $data = flattenToMultiDimensional($data,"|");
        $fp = fopen($filename, 'w');
        fwrite($fp, var_export($data, true));
        File::prepend($filename, '<?php return  ');
        File::append($filename, ';');
        \Artisan::call('cache:clear');
        return redirect()->route('setting.index', ['page' => 'language-setting'])->withSuccess( __('messages.updated'));
    }
}
