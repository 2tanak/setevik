<?php

namespace App\Http\Controllers\Oss;

use App\User;
use App\Models\Link;
use App\Models\Quittance;
use App\Models\BePartnerRequest;
use App\Services\Oss\TreeService;
use App\Models\Trees\BinaryTreeNode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BePartnerRequestController extends OssController
{
    public function index()
    {
		
        if (Auth::user()->isPartner()) {
            return redirect()->route('sib_news');
        }
        return view('oss.be_partner_request');
    }

    public function update(Request $request, $id)
    {
        try {
            $bePartnerRequest = BePartnerRequest::findOrFail($id);
            $user = Auth::user();

            if ($bePartnerRequest->user_id != $user->id) {
                throw new \Exception('Недостаточно прав');
            }

            //
            if ($request->has('link')) {
                $tree = new TreeService();
                $curator = $tree->getActiveCuratorSib($user);

                preg_match('/ref=(.*)/', $request->link, $matches);

                if (count($matches) != 2) {
                    throw new \Exception('Неккоректная ссылка');
                }

                if (Link::where('code', $matches[1])->count() != 1) {
                    throw new \Exception('Неккоректная ссылка');
                }

                $link = Link::where('code', $matches[1])->first();

                if ($link->user_id != $curator->id) {
                    throw new \Exception('Неккоректная ссылка. Обратитесь куратору.');
                }

                $decrypted = decrypt($link->code);
                if (BinaryTreeNode::findOrFail($decrypted['node_id'])->user_id) {
                    throw new \Exception('Узел занят. Получите новую ссылку у куратора.');
                }

                $user->tree_node_id = $decrypted['node_id'];
                $user->tree_inviter_node_id = $curator->tree_node_id;
                $user->save();

                $bePartnerRequest->curator_id = $curator->id;
                $bePartnerRequest->save();
            }

            $bePartnerRequest->update($request->all());

            return Response::json([
                'message' => 'done',
            ], 200);
        } catch (\Exception $e) {
            \Log::error(__METHOD__, ['message' => $e->getMessage(), 'user_id' => Auth::id(), 'data' => $request->all()]);
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $item = BePartnerRequest::where('user_id', Auth::id());

        if ($item->count()) {
            return $item->first();
        }

        return BePartnerRequest::create([
            'user_id' => Auth::id()
        ]);
    }

    public function fileUpload(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg|max:20480',
        ]);

        try {
            //
            if (!$request->hasFile('image')) {
                throw new \Exception('Необходимо прикрепить квитанцию');
            }

            $req        = BePartnerRequest::findOrFail($id);
            $user       = Auth::user();
            $quittance  = Quittance::create(['user_id' => $user->id]);

            //
            $quittance->attachFile($request->file('image'));

            // checking sender
            if ($req->user_id == $user->id) {
                $req->quittance()->associate($quittance)->save();
            } else {
                throw new \Exception('Недостаточно прав');
            }

            return Response::json([
                'message' => 'done',
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }
}
