<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Vaccination;

class VaccinationController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $society = Auth::user();

        $society->vaccinations->load('spot.regional');

        return response()->json([
            'vaccinations' => [
                'first' => $society->vaccinations->get(0),
                'second' => $society->vaccinations->get(1),
            ]
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
            'date' => 'nullable|date_format:Y-m-d',
            'spot_id' => 'required|exists:App\Models\Spot,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 401);
        }

        if ($society->consultation?->status != 'accepted') {
            return response()->json([
                'message' => 'Your consultation must be accepted by doctor before',
            ], 401);
        }

        if ($society->vaccinations->count() == 1 && now()->diffInDays($society->vaccinations->get(0)->date) < 30) {
            return response()->json([
                'message' => 'Wait at least +30 days from 1st Vaccination',
            ], 401);
        }
        if ($society->vaccinations->count() == 2) {
            return response()->json([
                'message' => 'Society has been 2x vaccinated',
            ], 401);
        }


        $data = $validator->validated();
        $data['date'] = isset($data['date']) ?  Carbon::parse($data['date']) : now();
        $data['society_id'] = $society->id;
        $data['dose'] = $society->vaccinations->count() == 0 ? 1 : 2;

        Vaccination::query()->create($data);

        return response()->json([
            'message' => ($data['dose'] == 1 ? 'First' : 'Second') . ' vaccination registered successful'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function show(Vaccination $vaccination) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vaccination $vaccination) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vaccination  $vaccination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vaccination $vaccination) {
        //
    }
}
