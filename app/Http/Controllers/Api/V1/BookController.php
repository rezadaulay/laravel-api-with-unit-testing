<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\V1\Book\Store;

use App\Book;

class BookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Book;
    }

    /*
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->model;
        if (isset($request->title) && trim($request->title) !== '') {
            $data = $data->where('title', 'LIKE', '%'.$request->title.'%');
        }
        if ($request->has('order') && in_array($request->order, ['title', 'description'])) {
            $data = $data->orderBy($request->input('order'), $request->input('ascending')? 'ASC' : 'DESC');
        } else {
            $data = $data->orderBy('title', 'ASC');
        }
        return $data->paginate($request->limit? $request->limit : 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\V1\Book\Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        \DB::beginTransaction();
		try{
            $data = $this->model->create([
                'title' => $request->title,
                'description' => $request->description
            ]);
            \DB::commit();
			return response()->json($data);
        }
        catch(\Exception $e){
            \DB::rollback();
			return response()->json([
				'errors' => $e->getMessage(),
			], in_array($e->getCode(), config('app.common_http_errors'))? $e->getCode() : 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
		try{
            $data = $this->model->findOrFail($id);
            $data->update([
                'title' => $request->has('title') ? $request->title : $data->title,
                'description' => $request->has('description') ? $request->description : $data->description
            ]);
            \DB::commit();
            return response()->json($data);
        }
        catch(\Exception $e){
            \DB::rollback();
			return response()->json([
				'errors' => $e->getMessage(),
			], in_array($e->getCode(), config('app.common_http_errors'))? $e->getCode() : 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->model->findOrFail($id);
        $data->delete();
        return response()->json(null);
    }
}
