<?php

namespace App\Http\Controllers\Oss;

class CompetitionsController extends OssController
{
    public function index()
    {
        return view('oss.competitions');
    }
}
