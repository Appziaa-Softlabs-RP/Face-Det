<?php

namespace App\Http\Controllers\google;
// require_once 'ProductsSample.php';

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleController extends Controller
{
    protected $productSample;
    public function __construct() {
        $this->productSample = new ProductsSample();
    }

    public function addProduct()
    {
        // $data = request()->all();
        // dd($data);
        // dd($this->productsSample->createExampleProduct($data));
        // $productData = $this->setProductData($data);
        // dd($productData);
        return response()->json([
            'products' => request()->all()
            // 'products' => $request->name
        ], 200);
    }

}
