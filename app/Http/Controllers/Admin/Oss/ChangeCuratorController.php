<?php

namespace App\Http\Controllers\Admin\Oss;

use App\User;
use App\Models\Cabinet;
use App\Services\Oss\TreeService;
use App\Events\User\ResidentCuratorChanged;
use App\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ChangeCuratorController extends AdminController
{
    protected $treeService;

    public function __construct(TreeService $treeService)
    {
        parent::__construct();
        $this->treeService = $treeService;
    }

    public function index()
    {
        $keyword = Input::get('q');

        if (strlen($keyword)) {
            $data = User::query()
//                ->where(function ($query) {
//                    $query
//                        ->where('cabinet_id', Cabinet::OSS)
//                        ->orWhere('is_oss_ever', true);
//                })
                ->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%")
                ->paginate(20000)
                ->appends(request()->query())
            ;
        } else {
            $data = User::where('cabinet_id', Cabinet::OSS)
                ->orWhere('is_oss_ever', true)
                ->orderBy('id', 'desc')
                //->paginate(20);
                ->get();
        }

        return view('admin.oss.change_curator')
            ->with('data', $data)
            ->with('q', $keyword);
    }

    public function curators(Request $request, $id)
    {
        $data = User::where('id', '!=', $id)
            ->where('cabinet_id', Cabinet::OSS)
            ->orWhere('is_oss_ever', true)
            ->get();

        return $data;
    }

    public function changeCurator(Request $request, $id)
    {
        $this->validate($request, [
            'curator_id' => 'required|exists:users,id',
        ]);

        $user = User::find($id);
        $curator = User::find($request->curator_id);

        $this->treeService->changeCurator($user, $curator);

        event(new ResidentCuratorChanged($user));
    }
}
