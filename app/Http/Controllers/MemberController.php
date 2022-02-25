<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $collection = Member::filter()->collective();

        return MemberResource::collection($collection);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:members,number",
        ]);

        $request->merge([
            'subtenant_id' => app()->runningInConsole() 
                ? $request->get('subtenant_id')
                : auth()->subtenant()->id,
        ]);

        $row = $request->only(['number', 'address', 'subtenant_id']);

        $record = Member::create($row);

        $message = "Member [number: $record->number] has been created";

        $record->createLog($message);

        return (new MemberResource($record))
            ->additional(["message" => $message]);
    }

    public function show($id)
    {
        $record = Member::findOrFail($id);

        return (new MemberResource($record));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:members,number,$id,id",
        ]);

        $record = Member::findOrFail($id);

        $row = $request->only(['number', 'address']);

        $record->update($row);

        $message = "Member [number: $record->number] has been updated";

        $record->createLog($message);

        return (new MemberResource($record))
            ->additional(["message" => $message]);
    }

    public function destroy($id)
    {
        $record = Member::findOrFail($id);

        $record->delete();

        $message = "Member [number: $record->number] has been deleted";

        $record->createLog($message);

        return response()->json(["message" => $message]);
    }
}
