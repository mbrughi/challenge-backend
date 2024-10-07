<?php

namespace App\Http\Controllers;

use App\Models\Persons;
use Illuminate\Http\Request;

class PersonsController extends Controller 
{

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return Persons::all();
        $persons = Persons::all();
        return response()->json($persons, 200);        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'nome' => 'required|string|max:255',
        	'cognome' => 'required|string|max:255',
        	'data_nascita' => 'required|date_format:d/m/Y',
        	'email' => 'required|email|unique:persons,email',
        	'telefono' => 'required|string|max:20',
        	'codice_fiscale' => 'required|string|unique:persons,codice_fiscale',
        ]);

        $persons = $request->user()->persons()->create($fields);

        return $persons;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //return $person;
        $person = Persons::findOrFail($id);
        return response()->json($person, 200);        
    }


    public function update(Request $request, $id)
    {
        $person = Persons::findOrFail($id);

        $this->authorize('update', $person);
        
        $request->validate([
            'nome' => 'required|string|max:255',
        	'cognome' => 'required|string|max:255',
        	'data_nascita' => 'required|date_format:d/m/Y',
        	'email' => 'required|email|unique:persons,email',
        	'telefono' => 'required|string|max:20',
        	'codice_fiscale' => 'required|string|unique:persons,codice_fiscale',
        ]);

        
        $person->update($request->all());

        return response()->json(['message' => 'Persona aggiornata con successo'], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persons $person)
    {       
        $person->delete();

        return ['message' => 'Il nominativo è stato cancellato'];
    }
}
