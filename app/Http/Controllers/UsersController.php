<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Models\Sector;

class UsersController extends Controller
{
    public function showUsers()
    {
        $users = User::orderBy('id', 'asc')->get();
        $profiles = Profile::orderBy('id','asc')->get();
        $sectors = Sector::orderBy('id','asc')->get();

        return view('usuarios.show_users', compact('users','profiles','sectors'));
    }
    

    public function detailUser($id)
    {
        $user = User::findOrFail($id);
        return view('usuarios.detail_user', compact('user'));   
    }
    

    public function update(Request $request){
        
        User::findOrFail($request->id)->update($request->all());
        
        return  redirect()->to(url()->previous())->with('msg', 'Registros Alterados com Sucesso!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect('/users')->with('msg', 'Registro excluído com sucesso!');
    }

    public function resetPass($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Assign plain password; the User model's mutator will hash it on save.
        $user->password = '123456789';
        $user->save();

        return response()->json(['message' => 'Senha atualizada com sucesso']);
    }

    public function create(Request $request){

        $user = new User;
        $user->name = $request->input('user_name');
        $user->email = $request->input('user_email');

        // Prefer explicit foreign keys when creating users
        $user->sector_id = $request->input('sector_id') ?: null;
        $user->profile_id = $request->input('profile_id') ?: null;
        // Optional NTE value for the user
        $user->nte = $request->input('nte') ?: null;

        // Assign plain password; the User model's mutator will hash it on save.
        $user->password = '123456789';

        $user->save();

        return back()->with('msg', 'success_create');

    }
}