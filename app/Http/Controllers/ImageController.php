<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Book;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Response|Application|ResponseFactory
    {
        $request->validate([
            'image_name' => 'required|string',
        ]);

        try {

            // If the file is not found, a 404 response will be returned
            if (!Storage::disk('public')->exists('images/' . $request->input('image_name'))) {
                abort(Response::HTTP_NOT_FOUND);
            }

            return response([
                'message' => 'ok',
                'image_url' => ImageHelper::getURL($request->input('image_name'))
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function upload(Request $request): Application|ResponseFactory|\Illuminate\Http\Response
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {

            $imageURL = ImageHelper::upload($request->file('image'));

            return response([
                'message' => 'ok',
                'data' => [
                    'image_name' => $imageURL,
                ],
            ], 200);
        } catch (Exception $e) {
            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadBookCovers(Request $request): \Illuminate\Http\Response|Application|ResponseFactory
    {
        $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'front_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'back_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $book = Book::find($request->input('book_id'));

            if ($request->hasFile('front_cover')) {
                $book->front_cover = ImageHelper::upload($request->file('front_cover'));
            }

            if ($request->hasFile('back_cover')) {
                $book->back_cover = ImageHelper::upload($request->file('back_cover'));
            }

            $book->save();

            return response([
                'message' => 'ok',
                'data' => [
                    'covers' => [
                        'front_cover' => $book->front_cover,
                        'back_cover' => $book->back_cover,
                    ],
                ],
            ], 200);
        } catch (Exception $e) {
            return response([
                'message' => 'error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
