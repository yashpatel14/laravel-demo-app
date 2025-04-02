<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $users = User::leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
        ->select('users.id', 'users.name', 'users.email', 'user_details.desc', 'user_details.image')
        ->get()
        ->groupBy('id'); // Group by user ID to properly structure the data

        // dd($users);

    return view('users.index', compact('users'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $validatedData = $request->validate([
            'users.*.name' => 'required',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => 'required|min:6',
            'users.*.details.*.desc' => 'required',
            'users.*.details.*.image' => 'nullable|image|max:2048',
        ]);

        foreach ($request->users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            if (isset($userData['details'])) {
                foreach ($userData['details'] as $detail) {
                    $imagePath = $detail['image'] ? $detail['image']->store('images', 'public') : null;
                    UserDetail::create([
                        'user_id' => $user->id,
                        'desc' => $detail['desc'],
                        'image' => $imagePath,
                    ]);
                }
            }
        }

        return back()->with('success', 'Users added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
