<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $userList=Redis::get("userList");
    $users = json_decode($userList);
    if($users==""){
    $users = User::leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
        ->select('users.id', 'users.name', 'users.email', 'user_details.desc', 'user_details.image')
        ->get()
        ->groupBy('id'); // Group by user ID to properly structure the data

        Redis::set("userList",$users);
    }
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
    public function edit($id)
{
    // Get user basic info
    $user = DB::table('users')->where('id', $id)->first();

    if (!$user) {
        abort(404, 'User not found');
    }

    // Get user details
    $details = DB::table('user_details')
                ->where('user_id', $id)
                ->get();

    return view('users.edit', compact('user', 'details'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'required',
        'email' => "required|email|unique:users,email,{$id}",
        'password' => 'nullable|min:6',
        'details.*.desc' => 'required',
        'details.*.image' => 'nullable|image|max:2048',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }
    $user->save();

    // Delete existing details and re-add (simplest approach)
    $user->details()->delete();

    if ($request->details) {
        foreach ($request->details as $detail) {
            $imagePath = isset($detail['image']) ? $detail['image']->store('images', 'public') : null;
            $user->details()->create([
                'desc' => $detail['desc'],
                'image' => $imagePath,
            ]);
        }
    }

    return redirect('/users')->with('success', 'User updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
