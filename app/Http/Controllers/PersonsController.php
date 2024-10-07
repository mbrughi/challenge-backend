<?php

namespace App\Http\Controllers;

use App\Models\Persons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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


/*    public function update(Request $request, $id)
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
    }*/
 
    public function update(Request $request, $id)
    {
        // Valida i campi inviati nella richiesta
        $fields = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'cognome' => 'sometimes|required|string|max:255',
            'data_nascita' => 'sometimes|required|date_format:d/m/Y',
            'email' => 'sometimes|required|email|unique:persons,email,' . $id,
            'telefono' => 'sometimes|required|string|max:20',
            'codice_fiscale' => 'sometimes|required|string|unique:persons,codice_fiscale,' . $id,
            'user_id' => 'sometimes|exists:users,id',
        ]);
    
        // Trova la persona nel database
        $person = Persons::find($id);
    
        if (!$person) {
            return response()->json(['message' => 'Person not found'], 404);
        }
    
        // Converti la data_nascita dal formato d/m/Y al formato Y-m-d per il database
        if (isset($fields['data_nascita'])) {
            $fields['data_nascita'] = \Carbon\Carbon::createFromFormat('d/m/Y', $fields['data_nascita'])->format('Y-m-d');
        }
    
        // Assegna i valori direttamente al modello
        foreach ($fields as $key => $value) {
            $person->$key = $value;
        }
    
        // Salva il modello
        $person->save();
    
        // Ricarica i dati dal database per riflettere le modifiche
        $person->refresh();
    
        // Restituisci la risposta con il record aggiornato
        return response()->json([
            'message' => 'Person updated successfully',
            'person' => $person
        ], 200);
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
