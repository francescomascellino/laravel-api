<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\FromLeadEmail;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // MANUAL VALIDATION
        $validator = Validator::make($request->all(), [
            'name' => 'required|mx:100',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        // SE LA VALIDAZIONE FALLISCE...
        if ($validator->fails()) {
            // RITORNA UNA RESPONSE FORMATO JSON CONTENENTE GLI ERRORI
            return response()->json([
                'sussess' => false,
                'errors' => $validator->errors()
            ]);
        }

        // SE LA VALIDAZIONE RIESCE, SALVA NEL DATABASE UNA ISTANZA DI Lead CONTENENTE I DATI DELLA Request
        $lead = Lead::create($request->all());

        // INVIA UNA MAIL ALL'ADMIN
        Mail::to('admin@portfolio.com')->send(new FromLeadEmail($lead)); // MY EMAIL ADRESS

        // INVIA UNA COPIA AL MITTENTE
        // Mail::to($lead->email)->send(new CREARE MODELLO($lead));

        // RITORNA AL FRONTEND UN JSON CON UN ESITO POSITIVO
        return response()->json(
            [
                'success' => true,
                'message' => 'mail sent'
            ]
        );
    }
}
