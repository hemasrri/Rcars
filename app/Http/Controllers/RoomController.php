<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Block;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
public function index(Request $request)
{
    $search = $request->input('search');
    $gender = $request->input('gender');
    $floor = $request->input('floor');
    $status = $request->input('status');

    $rooms = Room::with('block.hostel')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('floor_number', 'like', "%{$search}%")
                  ->orWhere('capacity', 'like', "%{$search}%")
                  ->orWhere('room_status', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%");
            });
        })
        ->when($gender, function ($query) use ($gender) {
            $query->where('gender', $gender);
        })
        ->when(isset($floor), function ($query) use ($floor) {
            $query->where('floor_number', $floor);
        })
        ->when($status, function ($query) use ($status) {
            $query->where('room_status', $status);
        })
        ->paginate(10);

    return view('admin.rooms.rooms', compact('rooms', 'search', 'gender', 'floor', 'status'));
}




    /**
     * Show the form for creating a new room.
     *
     * @return \Illuminate\View\View
     */
   public function create()
{
    $blocks = Block::with('hostel')->get(); // Load hostel relationship
    return view('admin.rooms.add-room', compact('blocks'));
}

    /**
     * Store a newly created room in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    $request->validate([
        'block_id' => 'required|exists:blocks,block_id',
        'room_number' => 'required|string|max:191',
        'floor_number' => 'required|integer',
        'capacity' => 'required|integer',
        'room_status' => 'required|string|max:191',
    ]);

    // Fetch the block to get gender, hostel_id, block_name
    $block = Block::where('block_id', $request->block_id)->firstOrFail();
    $gender = $block->gender;
    $hostelId = $block->hostel_id ?? null;

    // Generate room_id like ROO001, ROO002...
    $lastRoom = Room::orderBy('room_id', 'desc')->first();
    $nextNumber = $lastRoom ? ((int)substr($lastRoom->room_id, 3)) + 1 : 1;
    $roomId = 'ROO' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    try {
        Room::create([
            'room_id' => $roomId,
            'block_id' => $request->block_id,
            'room_number' => $block->block_name . '-' . $request->room_number, // 👈 Combined name
            'floor_number' => $request->floor_number,
            'capacity' => $request->capacity,
            'room_status' => $request->room_status,
            'gender' => $gender,
            'hostel_id' => $hostelId,
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create room: ' . $e->getMessage()]);
    }

    return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
}

    /**
     * Show the form for editing the specified room.
     *
     * @param Room $room
     * @return \Illuminate\View\View
     */
   public function edit(Room $room)
{
    $blocks = Block::with('hostel')->get(); // Load hostel relationship
    return view('admin.rooms.edit-room', compact('room', 'blocks'));
}

    /**
     * Update the specified room in storage.
     *
     * @param Request $request
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|string|max:191',
            'floor_number' => 'required|integer',
            'capacity' => 'required|integer',
            'room_status' => 'required|string|max:191',
        ]);

        try {
            $room->update([
                'room_number' => $request->room_number,
                'floor_number' => $request->floor_number,
                'capacity' => $request->capacity,
                'room_status' => $request->room_status,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update room: ' . $e->getMessage()]);
        }

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified room from storage.
     *
     * @param Room $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Room $room)
    {
        try {
            $room->delete();
        } catch (\Exception $e) {
            return redirect()->route('rooms.index')->withErrors(['error' => 'Failed to delete room: ' . $e->getMessage()]);
        }

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}