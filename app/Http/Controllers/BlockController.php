<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Hostel;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $search = $request->input('search');

        $blocks = Block::with('hostel')
            ->when($search, function ($query) use ($search) {
    return $query->where(function ($q) use ($search) {
        $q->where('block_name', 'like', '%' . $search . '%')
          ->orWhere('gender', 'like', '%' . $search . '%')
          ->orWhereHas('hostel', function ($hostelQuery) use ($search) {
              $hostelQuery->where('hostel_name', 'like', '%' . $search . '%');
          });
    });
})

            ->paginate(5);

        return view('admin.blocks.blocks', compact('blocks', 'search'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        return view('admin.blocks.add-block', compact('hostels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,hostel_id',
            'block_name' => 'required|string|max:191',
            'gender' => 'required|in:male,female,mix',
            'total_rooms' => 'required|integer',
            'total_floors' => 'nullable|integer',
        ]);

        $lastBlock = Block::orderBy('block_id', 'desc')->first();
        $nextNumber = $lastBlock ? ((int)substr($lastBlock->block_id, 3)) + 1 : 1;
        $blockId = 'BLK' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Block::create(array_merge($validated, ['block_id' => $blockId]));

return redirect()->route('blocks.index')->with('success', 'Block created successfully.');
    }

    public function edit($block_id)
    {
        $block = Block::findOrFail($block_id);
        $hostels = Hostel::all();
        return view('admin.blocks.edit-block', compact('block', 'hostels'));
    }

    public function update(Request $request, $block_id)
    {
        $block = Block::findOrFail($block_id);

        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,hostel_id',
            'block_name' => 'required|string|max:191',
            'gender' => 'required|in:male,female,mix',
            'total_rooms' => 'required|integer',
            'total_floors' => 'nullable|integer',
        ]);

        $block->update($validated);

        return redirect()->route('admin.blocks.index')->with('success', 'Block updated successfully.');
    }

    public function destroy($block_id)
    {
        $block = Block::findOrFail($block_id);
        $block->delete();

return redirect()->route('blocks.index')->with('success', 'Block deleted successfully.');
    }
}
