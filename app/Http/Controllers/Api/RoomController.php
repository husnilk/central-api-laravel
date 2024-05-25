<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomStoreRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Http\Resources\RoomCollection;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    public function index(Request $request): Response
    {
        $rooms = Room::all();

        return new RoomCollection($rooms);
    }

    public function store(RoomStoreRequest $request): Response
    {
        $room = Room::create($request->validated());

        return new RoomResource($room);
    }

    public function show(Request $request, Room $room): Response
    {
        return new RoomResource($room);
    }

    public function update(RoomUpdateRequest $request, Room $room): Response
    {
        $room->update($request->validated());

        return new RoomResource($room);
    }

    public function destroy(Request $request, Room $room): Response
    {
        $room->delete();

        return response()->noContent();
    }
}
