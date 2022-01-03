<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    // protected $validationDate = date('d/m') .'/'.(date('Y')-18);
    protected $validationRules = [
        'first_name' => 'string|max:50',
        'last_name' => 'string|max:50',
        'date_of_birth' => "date_format:Y-m-d|after:01/01/1900|before:18 years ago",
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        // if($user->id != Auth::user()->id){
        //     return redirect()->route('admin.apartments.index')->with('error', 'Non hai i permessi per accedere a questa pagina.');
        // }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if($user->id != Auth::user()->id){
            return redirect()->route('admin.apartments.index')->with('error', 'Non hai i permessi per accedere a questa pagina.');
        }
        $request->validate($this->validationRules);
        $user->fill($request->all());
        $user->update();

        return redirect()->route('admin.apartments.index')->with('success', 'Modifiche effettuate correttamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/');
    }
}
