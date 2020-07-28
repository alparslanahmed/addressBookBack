<?php

namespace App\Http\Controllers;

use App\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = Cache::remember('persons', 60*60, function() {
            return Person::orderBy('id', 'DESC')->get();
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Person $person
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Person $person)
    {
        return response()->json(['data' => $person]);
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
            'name' => 'required',
            'birthday' => 'required',
            'gender' => 'required'
        ]);

        $person = new Person([
            'name' => $request->get('name'),
            'birthday' => Carbon::parse($request->get('birthday')),
            'gender' => $request->get('gender')
        ]);

        $person->saveOrFail();
        Artisan::call('cache:clear');
        return response()->json(['data' => $person]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Person $person
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Person $person)
    {
        $person->name = $request->get('name');
        $person->gender = $request->get('gender');
        $person->birthday = Carbon::parse($request->get('birthday'));
        $person->saveOrFail();
        Artisan::call('cache:clear');
        return response()->json(['data' => $person]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Person $person
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Person $person)
    {
        $person->delete();
        Artisan::call('cache:clear');
        return response()->json(['type' => 'success']);
    }

}
