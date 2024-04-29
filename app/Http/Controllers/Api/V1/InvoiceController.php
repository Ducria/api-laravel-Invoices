<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Support\Facades\Validator as Validator;



class InvoiceController extends Controller
{
    use HttpResponses;
 

    public function __construct(){
        $this->middleware(['auth:sanctum', 'ability:invoice-store, user-update'])->only(['store','update']);
    }

    public function index(Request $request)
    {
        return (new Invoice())->filter($request);
    }

    public function store(Request $request)
    {

        if(!auth()->user()->tokenCan('invoice-store')){
            return $this->error('Unauthorized', 403);
        }
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'type' => 'required',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99',
        ]);

        if($validator->fails()){
            return $this->error('Data Invalid', 422, $validator->errors());
        }

        $created = Invoice::create($validator->validated());

        if($created){
            return $this->response('Invoice created', 200, new InvoiceResource($created->load('user')));
        }

        return $this->error('Invoice not created', 400);
    
    }
        
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'user_id'=> 'required',
            'type' => 'required|max:1|in:'.implode(',', ['P', 'B', 'C']),
            'paid' => 'required|numeric|between:0,1',
            'value' => 'required|numeric',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        if($validator->fails()){
            return $this->error('Validate Error', 442, $validator->errors());
        }

        $validated = $validator->validated();

        $invoice = Invoice::find($id);

        $updated = $invoice->update([

            'user_id'=> $validated['user_id'],
            'type' => $validated['type'],
            'paid' => $validated['paid'],
            'value' => $validated['value'],
            'payment_date' => $validated['paid'] ? $validated['payment_date'] : null
        ]);

        if($updated){
            return $this->response('Invoice Updated', 200, new InvoiceResource($invoice->load('user')));
        };
        
        return $this->error('Invoice not Updated', 400);

        
    }

    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if($deleted){
            return $this->response('Invoice Deleted', 200);
        }

        return $this->error('Invoice not Deleted', 400);

    }
}
