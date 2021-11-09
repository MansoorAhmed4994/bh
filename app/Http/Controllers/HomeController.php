<?php

namespace App\Http\Controllers;
use CountryState;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $countries = new Countries();

        // //$all = $countries->all();
        // //dd($all);
        // // $countries = CountryState::getCountries();
        // // $states = CountryState::getStates('PK');
        // // dd($states);
        // // dd($countries->where('cca3', 'USA')->first()->hydrate('cities')->cities);
        // dd($countries->where('cca3', 'CHN')->first()->hydrateStates()->states->pluck('name', 'postal')->toArray());
    
        return view('home');
    }
}
