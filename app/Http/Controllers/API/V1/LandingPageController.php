<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BaseController as BaseController;

class LandingPageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = array (
            'success' => true,
            'warnings' => 
            array (
            ),
            'errors' => 
            array (
            ),
            'requestId' => '7a39#154cf7922c6',
            'result' => 
            array (
              0 => 
              array (
                'id' => 27,
                'name' => 'createLandingPage',
                'description' => 'this is a test',
                'createdAt' => '2016-05-20T18:41:43Z+0000',
                'updatedAt' => '2016-05-20T18:41:43Z+0000',
                'folder' => 
                array (
                  'type' => 'Folder',
                  'value' => 11,
                  'folderName' => 'Landing Pages',
                ),
                'workspace' => 'Default',
                'status' => 'draft',
                'template' => 1,
                'title' => 'test create',
                'keywords' => 'awesome',
                'robots' => 'index, nofollow',
                'formPrefill' => false,
                'mobileEnabled' => false,
                'URL' => 'https://app-devlocal1.marketo.com/lp/622-LME-718/createLandingPage.html',
                'computedUrl' => 'https://app-devlocal1.marketo.com/#LP27B2',
              ),
            ),
        );
        
        return $this->sendResponse($cities, 'Cities successfully Retrieved...!');  
    }
}
