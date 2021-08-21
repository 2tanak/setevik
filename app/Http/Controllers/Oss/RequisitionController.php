<?php

namespace App\Http\Controllers\Oss;

use App\Models\File;
use App\Models\Product;
use App\Models\Quittance;
use App\Models\Requisition;
use App\Services\Oss\TreeService;
use App\Services\Oss\ProductService;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Validator;

/**
 * @deprecated
 * @package App\Http\Controllers\Oss
 */
class RequisitionController extends Controller
{
    protected $tree;
    protected $productService;

    public function __construct(TreeService $tree, ProductService $productService)
    {
        $this->middleware('role:resident,resident-na,partner,partner-na');
        $this->tree = $tree;
        $this->productService = $productService;
    }

    /**
     * View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // todo: can't remove this, because used method 'store()' in this class
        if (auth()->user()->hasRole('resident-na', 'partner-na')) {
            return redirect()->route('home');
        }

        $requisitions = Requisition::with(['product', 'user', 'requisitionType'])
            ->where('curator_id', Auth::id())
            ->where(function ($query) {
                $query
                    ->where('is_confirmed', true)
                    ->orWhere('user_quittance_id', '!=', null);
            })
            ->orderByDesc('id')
            ->get();

        return view('oss.requisitions')->with('data', $requisitions);
    }

    /**
     * Create new requisition with file (or without)
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
			
			 // validation
            if ($request->hasFile('quittance')) {
			$messages = ['quittance.mimes'=>'Допустимые файлы jpeg, jpg, png, pdf, размер не более 20МБ','quittance.max'=>'Данный файл не может быть загружен так как слишком большой или не является формата PDF, jpeg, png','productId.required'=>'Не найден id продукта','productId.exists'=>'В базе нет такого продукта'];
	        $validator = Validator::make($request->all(),[
				'quittance' => 'file|mimes:jpg,jpeg,JPEG,jpg,png,pdf|max:20480',
				'productId' => 'required|exists:products,id',
			],$messages);
			}else{
				$messages = ['productId.required'=>'Не найден id продукта','productId.exists'=>'В базе нет такого продукта'];
	            $validator = Validator::make($request->all(),[
				'productId' => 'required|exists:products,id',
			],$messages);
				
			}
			if($validator->fails()) {
			$view = view('errors.validator_errors')->with(['error' => $validator->errors()])->render();
			   return response()->json([
                'view' => $view,
				'html'=>'html'
            ], 200);
		   }
			
			
			
			
			
			

            $user = Auth::user();
            $curator = $this->tree->getActiveCurator($user);
            $product = Product::findOrFail($request->input('productId'));
            $requisitionTypeId = null;

            if (Requisition::where('user_id', $user->id)->count()) {
                if ($this->productService->hasSubscription($product, $user)) {
                    $requisitionTypeId = 2;
                } else {
                    $requisitionTypeId = 3;
                }
            } else {
                if ($user->isResident()) {
                    $requisitionTypeId = 1;
                } elseif($user->isPartner()) {
                    $requisitionTypeId = 4;
                }
            }

//            // check requisition type
//            if ($user->is_active == false) {
//                $requisitionTypeId = 1;
//            } else {
//                if ($this->productService->hasSubscription($product, $user)) {
//                    $requisitionTypeId = 2;
//                } else {
//                    $requisitionTypeId = 3;
//                }
//            }

            // store new requisition
            $requisition = Requisition::create([
                'user_id' => $user->id,
                'curator_id' => $curator->id,
                'product_id' => $product->id,
                'requisition_type_id' => $requisitionTypeId,
            ]);

            // upload file if sent
            if ($request->hasFile('quittance')) {

                $quittance = Quittance::create(['user_id' => $user->id]);
                $quittance->attachFile($request->file('quittance'));
                $requisition->userQuittance()->associate($quittance)->save();
            }

            return Response::json([
                'message' => 'done',
                'requisition_id' => $requisition->id,
            ], 200);

        } catch (ValidationException $e)  {
            Log::error(sprintf('%:%s', __LINE__, __METHOD__), [$e->getMessage()]);
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        } catch (\Exception $e) {
            Log::error(sprintf('%:%s', __LINE__, __METHOD__), [$e->getMessage()]);
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Upload curator's quittance screenshot (file)
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function curatorFileUpload(Request $request, $id)
    {
        try {
		

			$messages = ['quittance.mimes'=>'Допустимые файлы jpg, png, pdf, размером не более 20мб','quittance.max'=>'Допустимый размер файла не более 20480 МБ'];

	        $validator = Validator::make($request->all(),[
				'quittance' => 'file|mimes:jpg,jpeg,JPEG,jpg,png,pdf|max:20480',
			],$messages);
			
			if($validator->fails()) {
				
	         $view = view('errors.validator_errors')->with(['error' => $validator->errors()])->render();
			   return response()->json([
                'view' => $view,
				'html'=>'html'
            ], 200);
		   }
			
			


            //
            if (!$request->hasFile('quittance')) {
                throw new \Exception('Необходимо прикрепить квитанцию');
            }

            $requisition    = Requisition::findOrFail($id);
            $user           = Auth::user();
            $quittance      = Quittance::create(['user_id' => $user->id]);

            //
            $quittance->attachFile($request->file('quittance'));

            // checking sender
            if ($requisition->user_id == $user->id) {
                $requisition->userQuittance()->associate($quittance)->save();
            } elseif ($requisition->curator_id == $user->id) {
                $requisition->curatorQuittance()->associate($quittance)->save();
            } else {
                throw new \Exception('Недостаточно прав');
            }

            return Response::json([
                'message' => 'done',
            ], 200);
        } catch (\Exception $e) {
				return 13;
            return Response::json([
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Upload file (quittance)
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function fileUpload(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'file|mimes:jpeg,png,jpg,pdf|max:20480',
        ]);

        try {
            //
            if (!$request->hasFile('image')) {
                throw new \Exception('Необходимо прикрепить квитанцию');
            }

            $requisition    = Requisition::findOrFail($id);
            $user           = Auth::user();
            $quittance      = Quittance::create(['user_id' => $user->id]);

            //
            $quittance->attachFile($request->file('image'));

            // checking sender
            if ($requisition->user_id == $user->id) {
                $requisition->userQuittance()->associate($quittance)->save();
            } elseif ($requisition->curator_id == $user->id) {
                $requisition->curatorQuittance()->associate($quittance)->save();
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

    //Отмена заявки на продление абонемента
    public function requisitioncancel(Request $request)
    {
        $data = $request->all();
        $requisition_id = $data['id'];

        // store new requisition
        $requisition = Requisition::find($requisition_id);
        $requisition->is_confirmed = 2;
        $requisition->is_cancelled = 1;
        $requisition->save();

        print_r($requisition_id);
    }

}
