<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->orderBy('time', 'DESC')->get();

        return response()->json([
            'message' => 'List transaction order by time',
            'data' => $transaction,
            // 'id' => Auth::user()->id
        ], Response::HTTP_OK);
    }

//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue'],

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // $transaction = Transaction::create([
            //     'title' => $request->title,
            //     'amount' => $request->amount,
            //     'type' => $request->type,
            //     'user_id' => Auth::user()->id
            // ]);
            $transaction = new Transaction();
            $transaction->title = $request->title;
            $transaction->amount = $request->amount;
            $transaction->type = $request->type;
            $transaction->user_id = Auth::user()->id;
            $transaction->save();

            $response = [
                'message' => 'Transaction created',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $response = [
                'message' => 'List transaction order by time',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Id not found'
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }


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
