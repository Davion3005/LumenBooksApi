<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return list of books
     *
     * @return Response
     */
    public function index()
    {
        $book = Book::all();

        return $this->successResponse($book);
    }

    /**
     * Create one new book
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'price' => 'required|min:1',
            'author_id' => 'required|min:1'
        ];
        $this->validate($request, $rules);
        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * Obtains and shows one book
     *
     * @return Response
     */
    public function show($book)
    {
        $book = Book::findOrFail($book);

        return $this->successResponse($book);
    }

    /**
     * Updates an existing book
     *
     * @return Response
     */
    public function update(Request $request, $book)
    {
        $rules = [
            'title' => 'max:255',
            'description' => 'max:1000',
            'price' => 'min:1',
            'author_id' => 'min:1'
        ];
        $this->validate($request, $rules);
        $book = Book::findOrFail($book);
        $book->fill($request->all());
        if ($book->isClean()) {
            return $this->errorResponse('At least one field must be changed', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $book->save();

        return $this->successResponse($book);
    }

    /**
     * Deletes an existing book
     *
     * @return Response
     */
    public function destroy($book)
    {
        $book = Book::findOrFail($book);
        $book->delete();

        return $this->successResponse($book);
    }
}
