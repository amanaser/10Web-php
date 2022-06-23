<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //    public function store(Request $request)
//    {
//        $validatedData = $request->validate([
//            'name' => ['required', 'max:255', 'min:3'],
//            'parent_id' => ['nullable', 'exists:categories,id'],
//        ]);
//        try {
//            $category = new Category();
//            $category->fill([
//                'name' => $request->name,
//                'parent_id' => $request->parent_id,
//            ])->save();
//
//            return response()->json([
//                'status' => 1,
//                'message' => 'Category created successfully'
//            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'status' => 0,
//                'message' => $e->getMessage()
//            ]);
//        }
//
//    }

    public function store(StoreCategoryRequest $request)
    {
        $newCategory = new Category();
        $newCategory -> fill($request -> all());
        $newCategory -> save();

        return response() -> json([
            'status' => 1,
            'message' => 'The category was created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
