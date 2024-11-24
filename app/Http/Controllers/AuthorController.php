<?php

namespace App\Http\Controllers;

use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Models\author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Author::all();
    }

    public function show(Author $author){
        return $author;

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        //
        $select_author = Author::where([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth' => $request->birth
        ])->first();

        if(!$select_author){
            $author = Author::create($request->all());
            return response()->json([
                "success" => true,
                "message" => "Author created successfully",
                "author_id" => $author->id
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'This author already exist',
            'author_id' => $select_author->id,
        ], 409);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
        $attempt = $author->update($request->all());
        if(!$attempt){
            return response()->json([
                "success" => false,
                "message" => "Author not updated",
            ], 422);
        }
        return response()->json([
            "success" => true,
            "message" => "Author updated successfully",
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(author $author)
    {
        //
        $attempt = $author->delete();
        if (!$attempt) {
            return response()->json([
                "success" => false,
                "message" => "Author not deleted",
            ],422);
        }
        return response()->json([
            "success" => true,
            "message" => "Author deleted successfully",
        ]);
    }
}
