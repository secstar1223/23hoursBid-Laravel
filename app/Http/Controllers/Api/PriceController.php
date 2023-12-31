<?php
namespace App\Http\Controllers\Api;

use App\Models\EquipmentType;
use App\Models\Price;
use Illuminate\Http\Request;
use App\Models\RentalProducts;
use App\Models\Duration;
use App\Http\Controllers\Api\BaseController as BaseController;
use Carbon\Carbon;

class PriceController extends BaseController
{
    protected function getDetail($price): array
    {

        return [
            'id' => $price->id,
            'duration' => Duration::find($price->duration_id),
            'equipment' => EquipmentType::find($price->equipment_id),
            'total' => $price->total,
            'deposit' => $price->deposit,
        ];
    }

    public function index($product_id, $equipment_id)
    {
        $user = auth()->user();
        $success['prices'] = [];
        if ($product_id == 0) {
            $products = RentalProducts::where('team_id', $user->current_team_id)->get();
            foreach ($products as $product) {
                if ($product->prices != []) {
                    if ($equipment_id == 0) {
                        foreach ($product->prices as $price) {
                            $success['prices'][] = $this->getDetail($price);
                        }
                    } else {
                        $prices = Price::where(['equipment_id' => $equipment_id])->get();
                        foreach ($prices as $price) {
                            $success['prices'][] = $this->getDetail($price);
                        }
                    }
                }
            }
        } else {
            $currentproduct = RentalProducts::find($product_id);
            if (!$currentproduct) {
                $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
                return $this->sendError($responseMessage, 500);
            }
            if ($equipment_id == 0) {
                foreach ($currentproduct->prices as $price) {
                    $success['prices'][] = $this->getDetail($price);
                }
            } else {
                $prices = Price::where(['product_id' => $product_id, 'equipment_id' => $equipment_id])->get();
                foreach ($prices as $price) {
                    $success['prices'][] = $this->getDetail($price);
                }
            }
        }
        return $this->sendResponse($success, null);
    }

    public function store(Request $request, $equipment_id, $product_id)
    {
        $prices = new Price();
        $prices->total = $request->total;
        $prices->deposit = $request->deposit;
        $prices->equipment_id = $request->equipment_id;
        $prices->duration_id = $request->duration_id;
        $prices->product_id = $product_id;
        $prices->save();
        $responseMessage = "Price created successfully.";
        return $this->sendResponse($prices, $responseMessage);
    }

    public function getById($product_id, $equipment_id, $id)
    {
        $price = Price::find($id)->first();
        if (!$price) {
            $responseMessage = "The specified Price does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }
        $success['price'] = $this->getDetail($price);
        return $this->sendResponse($success, null);
    }

    public function update(Request $request, $product_id, $equipment_id, $id)
    {

        $currentproduct = RentalProducts::whereId($product_id)->get();

        if (!$currentproduct) {
            $responseMessage = "The specified rental product does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $price = Price::find($id);
        if (!$price) {
            $responseMessage = "The specified Price does not exist or is not associated with the current team.";
            return $this->sendError($responseMessage, 500);
        }

        $price->total = $request->total;
        $price->deposit = $request->deposit;
        $price->equipment_id = $request->equipment_id;
        $price->duration_id = $request->duration_id;
        $price->product_id = $request->product_id;
        $price->save();
        $sucess['price'] = $price;
        $responseMessage = "Current Price updated successfully.";
        return $this->sendResponse($sucess, $responseMessage);
    }

    public function destroy($product_id, $equipment_id, $id)
    {
        $ids = explode(",", $id);
        $deletePrice = Price::whereIn('id', $ids)->delete();
        if ($deletePrice == 0) {
            $responseMessage = 'The specified Price does not exist or is not associated with the current Rental Product.';
            return $this->sendError($responseMessage, 500);
        }
        $responseMessage = "Price Type deleted successfully.";
        return $this->sendResponse([], $responseMessage);
    }
}
