<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Contact;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController as BaseController;

class ContactController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::paginate(10);

        return $this->sendResponse($contacts, 'Contacts successfully Retrieved...!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'sometimes|numeric',
            'product_id' => 'sometimes|numeric',
            'user_id' => 'sometimes|numeric',
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|between:2,200',
            'phone' => 'required|numeric',           
            'contact_meta' => 'nullable',
            'message' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $contacts = Contact::create($request->all());

        return $this->sendResponse($contacts, 'Query submited successfully...!');    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        
        if (is_null($contact)) {
            return $this->sendError('Empty', [], 404);
        }

        return $this->sendResponse($contact, 'Contact successfully Retrieved...!');  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'sometimes|numeric',
            'product_id' => 'sometimes|numeric',
            'user_id' => 'sometimes|numeric',
            'name' => 'string|between:2,100',
            'email' => 'string|email|between:2,200',
            'phone' => 'numeric',           
            'contact_meta' => 'nullable',
            'message' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), '', 400);       
        }

        $contact = Contact::find($id);

        if (is_null($contact)) {
            return $this->sendError('Empty', [], 404);
        }

        $contact->update($request->all());

        return $this->sendResponse($contact, 'contacts updated successfully...!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (is_null($contact)) {
            return $this->sendError('Empty', [], 404);
        }

        $contact->delete($id);

        return $this->sendResponse($contact, 'contact deleted successfully...!');   
    }
}
