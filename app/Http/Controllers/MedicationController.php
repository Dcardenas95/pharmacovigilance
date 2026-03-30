<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function search(Request $request)
    {
        $query = Medication::query();

        // Search by lot number
        if ($request->has('lot') && $request->lot) {
            $query->where('lot_number', $request->lot);
        }

        // Search by medication name
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $medications = $query->get();

        return response()->json([
            'success' => true,
            'data' => $medications
        ]);
    }
}
