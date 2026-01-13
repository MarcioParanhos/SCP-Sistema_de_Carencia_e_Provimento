<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function showUsers()
    {
        $users = User::orderBy('id', 'asc')->get();

        return view('usuarios.show_users', compact('users'));
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
        $user->name = $request->user_name;
        $user->setor = $request->user_sector;
        $user->profile = $request->user_profile;
        $user->email = $request->user_email;
        // Assign plain password; the User model's mutator will hash it on save.
        $user->password = '123456789';

        $user->save();

        return back()->with('msg', 'success_create');

    }
}