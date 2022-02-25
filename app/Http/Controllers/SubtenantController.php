<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubtenantResource;
use App\Models\Subtenant;
use Illuminate\Http\Request;

class SubtenantController extends Controller
{
    public function index()
    {
        $collection = Subtenant::filter()->collective();

        return SubtenantResource::collection($collection);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:subtenants,number",
            "name" => "required|string",
        ]);

        $request->merge([
            'tenant_id' => app()->runningInConsole() 
                ? $request->get('tenant_id')
                : auth()->tenant()->id,
        ]);

        $row = $request->only(['number', 'name', 'tenant_id']);

        $record = Subtenant::create($row);

        $message = "Subtenant [number: $record->number] has been created";

        $record->createLog($message);

        return (new SubtenantResource($record))
            ->additional(["message" => $message]);
    }

    public function show($id)
    {
        $record = Subtenant::findOrFail($id);

        return (new SubtenantResource($record));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:subtenants,number,$id,id",
            "name" => "required|string",
        ]);

        $record = Subtenant::findOrFail($id);

        $row = $request->only(['number', 'name']);

        $record->update($row);

        $message = "Subtenant [number: $record->number] has been updated";

        $record->createLog($message);

        return (new SubtenantResource($record))
            ->additional(["message" => $message]);
    }

    public function destroy($id)
    {
        $record = Subtenant::findOrFail($id);

        $record->delete();

        $message = "Subtenant [number: $record->number] has been deleted";

        $record->createLog($message);

        return response()->json(["message" => $message]);
    }
}
