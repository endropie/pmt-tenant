<?php

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Models\Member;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index()
    {
        $collection = Person::filter()->collective();

        return PersonResource::collection($collection);
    }

    public function store(Member $member, Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:persons,number",
            "name" => "required|string",
            "gender" => "nullable",
            "birth_date" => "nullable|date_format:Y-m-d",
            "birth_place" => "nullable|string",
        ]);

        $row = $request->only(['number', 'name', 'gender', 'birth_date', 'birth_place', 'address']);

        $record = $member->persons()->create($row);

        $message = "Person [number: $record->number] has been created";

        $record->createLog($message);

        return (new PersonResource($record))
            ->additional(["message" => $message]);
    }

    public function show($id)
    {
        $record = Person::findOrFail($id);

        return (new PersonResource($record));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            "number" => "required|unique:persons,number,$id,id",
            "name" => "required|string",
            "gender" => "nullable",
            "birth_date" => "nullable|date_format:Y-m-d",
            "birth_place" => "nullable|string",
        ]);

        $record = Person::findOrFail($id);

        $row = $request->only(['number', 'name', 'gender', 'birth_date', 'birth_place', 'address']);
        $record->update($row);

        $message = "Person [number: $record->number] has been updated";

        $record->createLog($message);

        return (new PersonResource($record))
            ->additional(["message" => $message]);
    }

    public function destroy($id)
    {
        $record = Person::findOrFail($id);

        $record->delete();

        $message = "Person [number: $record->number] has been deleted";

        $record->createLog($message);

        return response()->json(["message" => $message]);
    }
}
