<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function person_addresses($person_id)
    {
        $data = Cache::remember('addresses/' . $person_id, 60 * 60, function () use ($person_id) {
            return Address::where('person_id', $person_id)->orderBy('id', 'DESC')->get();
        });

        return response()->json(['data' => $data]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'address' => 'required',
            'city_name' => 'required',
            'country_name' => 'required',
            'postal_code' => 'required'
        ]);

        $person = new Address([
            'address' => $request->get('address'),
            'city_name' => $request->get('city_name'),
            'country_name' => $request->get('country_name'),
            'postal_code' => $request->get('postal_code'),
            'person_id' => $request->get('person_id')
        ]);

        $person->saveOrFail();
        Artisan::call('cache:clear');
        return response()->json(['data' => $person]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Address $address)
    {
        return response()->json(['data' => $address]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Address $address)
    {
        $address->address = $request->get('address');
        $address->city_name = $request->get('city_name');
        $address->country_name = $request->get('country_name');
        $address->postal_code = $request->get('postal_code');
        $address->saveOrFail();
        Artisan::call('cache:clear');
        return response()->json(['data' => $address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Address $address)
    {
        $address->delete();
        Artisan::call('cache:clear');
        return response()->json(['type' => 'success']);
    }
}
