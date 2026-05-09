<?php

namespace App\Http\Controllers\admin;
// namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\TypeChambre;
use App\Models\Equipement;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Chambre::with(['typeChambre', 'equipements', 'propriete'])->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $types = TypeChambre::all();
        $equipements = Equipement::all();
        return view('admin.rooms.create', compact('types', 'equipements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
