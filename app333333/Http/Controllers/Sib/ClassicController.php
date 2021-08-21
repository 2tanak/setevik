<?php

namespace App\Http\Controllers\Sib;

use App\User;
use App\Services\Sib\ClassicTreeService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassicController extends Controller
{
    protected $treeService;

    public function __construct(ClassicTreeService $treeService)
    {
        $this->middleware('role:partner');
        $this->treeService = $treeService;
    }

    public function index()
    {
        $data = $this->treeService->getTreeArray(Auth::user());

        $totalCount = 0;
        foreach ($data as $datum) {
            $totalCount += count($datum);
        }

        return view('sib.classic', compact('data', 'totalCount'));
    }
}
