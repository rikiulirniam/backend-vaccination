<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Spot;
use App\Models\Vaccination;

class SpotController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $society = Auth::user();
        $spots = $society->regional->spots;
        $spots->makeVisible('available_vaccines');


        return response()->json([
            'spots' => $spots
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spot  $spot
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Spot $spot) {
        $spot->makeHidden('available_vaccines');

        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $validator->validated();
        $data['date'] = isset($data['date']) ?  Carbon::parse($data['date']) : now();

        $vaccinationsCount = Vaccination::query()
            ->where('date', $data['date'])
            ->where('spot_id', $spot->id)
            ->get()->count();

        return response()->json([
            'date' => $data['date']->toFormattedDateString(),
            'spot' => $spot,
            'vaccinations_count' => $vaccinationsCount
        ]);
    }
}
