<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $collection = Tenant::filter()->collective();

        return TenantResource::collection($collection);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:tenants,number",
            "name" => "required|string",
        ]);

        $request->merge([
            'owner_id' => app()->runningInConsole() 
                ? $request->get('owner_id')
                : auth()->user()->id,
        ]);

        $row = $request->only(['number', 'name', 'owner_id']);

        $record = Tenant::create($row);

        $message = "Tenant [number: $record->number] has been created";

        $record->createLog($message);

        return (new TenantResource($record))
            ->additional(["message" => $message]);
    }

    public function show($id)
    {
        $record = Tenant::findOrFail($id);

        return (new TenantResource($record));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:tenants,number,$id,id",
            "name" => "required|string",
        ]);

        $record = Tenant::findOrFail($id);

        $row = $request->only(['number', 'name']);

        $record->update($row);

        $message = "Tenant [number: $record->number] has been updated";

        $record->createLog($message);

        return (new TenantResource($record))
            ->additional(["message" => $message]);
    }

    public function destroy($id)
    {
        $record = Tenant::findOrFail($id);

        $record->delete();

        $message = "Tenant [number: $record->number] has been deleted";

        $record->createLog($message);

        return response()->json(["message" => $message]);
    }
}
