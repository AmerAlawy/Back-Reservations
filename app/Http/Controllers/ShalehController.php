<?php

namespace App\Http\Controllers;
use App\Models\Shaleh;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ShalehController extends Controller
{ 
    public function myShalehs()
{
    $shalehs = Shaleh::where('user_id', Auth::id())
        ->latest()
        ->get();

    return response()->json([
        'message' => 'My shalehs retrieved successfully',
        'data' => $shalehs
    ], 200);
}
public function createShaleh(Request $request)
{
    $user = Auth::id();
     
    $validate = $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('myPhotos', 'public');
        $validate['image'] = $path;
    }

    $validate['user_id'] = $user;
     $validate['status'] = 'pending';
    $shaleh = Shaleh::create($validate);

    return response()->json([
        'message' => 'Shaleh created successfully',
        'data' => $shaleh
    ], 201);
}
           public function show($id)
{
    $shaleh = Shaleh::findOrFail($id);

    $user = Auth::user();

    if (
        $shaleh->status !== 'approved' &&
        $shaleh->user_id !== $user->id &&
        $user->role !== 'admin'
    ) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    return response()->json($shaleh, 200);
}
   public function getAllShalehs()
{
    $shalehs = Shaleh::where('status', 'approved')
        ->latest()
        ->get();

    return response()->json($shalehs, 200);
}
   public function pendingShalehs()
{
    $shalehs = Shaleh::with('user:id,name,email')
        ->where('status', 'pending')
        ->latest()
        ->get();

    return response()->json([
        'message' => 'Pending shalehs retrieved successfully',
        'data' => $shalehs
    ], 200);
}
public function approveShaleh($id)
{
    $shaleh = Shaleh::findOrFail($id);

    if ($shaleh->status !== 'pending') {
        return response()->json([
            'message' => 'This shaleh is not pending'
        ], 409);
    }

    $shaleh->status = 'approved';
    $shaleh->save();

    return response()->json([
        'message' => 'Shaleh approved successfully',
        'data' => $shaleh
    ], 200);
}
public function rejectShaleh($id)
{
    $shaleh = Shaleh::findOrFail($id);

    if ($shaleh->status !== 'pending') {
        return response()->json([
            'message' => 'This shaleh is not pending'
        ], 409);
    }

    $shaleh->status = 'rejected';
    $shaleh->save();

    return response()->json([
        'message' => 'Shaleh rejected successfully',
        'data' => $shaleh
    ], 200);
}
}
