<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Consultation;

class ConsultationController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $society = Auth::user();

        return response()->json([
            'consultation' => $society->consultation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $society = Auth::user();
        $validator = Validator::make($request->all(), [
            'disease_history' => 'nullable',
            'current_symptoms' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $validator->validated();
        $data['society_id'] = $society->id;
        $data['status'] = 'pending';

        Consultation::query()->create($data);

        return response()->json([
            'message' => 'Request consultation sent successful'
        ]);
    }
}
