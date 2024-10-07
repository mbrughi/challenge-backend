<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Controllo se l'utente loggato è un admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Se l'utente è admin, restituisco tutti gli utenti
        $users = User::all();
        return response()->json($users);
    }

    // Cancellare un utente
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utente cancellato con successo'], 200);
    }

    // Aggiornare il ruolo di un utente
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,editor,viewer',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'Ruolo aggiornato con successo'], 200);
    }    
}
