<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Review::query();

            if ($request->has("product_id")) {
                $query->where("product_id", $request->product_id);
            }

            if ($request->has("user_id")) {
                $query->where("user_id", $request->user_id);
            }

            $reviews = $query->with(["user", "product"])->get();

            return response()->json(["reviews" => $reviews]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "user_id" => "required|integer|exists:users,id",
                "product_id" => "required|integer|exists:products,id",
                "rating" => "required|integer|min:1|max:5",
                "comment" => "nullable|string",
            ]);

            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()->first()], 400);
            }

            $review = Review::create($request->all());

            return response()->json(["message" => "Avaliação criada com sucesso!", "review" => $review], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $review = Review::with(["user", "product"])->find($id);

            if (!$review) {
                return response()->json(["error" => "Avaliação não encontrada"], 404);
            }

            return response()->json(["review" => $review]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
