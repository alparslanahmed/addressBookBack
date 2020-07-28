<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => Address::orderBy('id', 'DESC')->get()]);
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
            'postal_code' => $request->get('postal_code')
        ]);

        $person->saveOrFail();

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
        return response()->json(['type' => 'success']);
    }
}
