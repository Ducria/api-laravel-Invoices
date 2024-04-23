<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class InvoiceResource extends JsonResource
{
    private array $types = ['C' => 'Cartão', 'B' => 'Boleto', 'P' => 'Pix'];

    
    public function toArray(Request $request): array
    {
        return[
            'user' =>[
                'firstName' => $this->user->firstName,
                'lastName' => $this->user->lastName,   
                'fullName' => $this->user->firstName.' '. $this->lastName,
                'email' => $this->user->email,  
            ],
            'type' => $this->types[$this->type],
            'value' => 'R$ '. number_format($this->value, 2, ',','.'),
            'paid' => $this->paid ? 'Pago' : 'Não Pago',
            'paymentDate' => $this->paid ? Carbon::parse($this->payment_date)->format('d/m/Y H:i:s') : Null,
            'paymentSince' => $this->paid ? Carbon::parse($this->payment_date)->diffForHumans() : Null,

        ];
    }
}
