<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function getSomeColumns()
    {
        try {
            $user = User::SomeColumns();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getByID($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json($user);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function save(Request $request)
    {
        try {
            $validateDataUser = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'cpf' => [
                    'required',
                    'string',
                    'unique:users,cpf',
                    'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/'
                ],
                'birth_date' => [
                    'required',
                    'date',
                    'before:today',
                ],
                'phone' => 'string|min:11',
            ], [
                'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
                'birth_date.before' => 'A data de nascimento deve ser uma data no passado.',
            ]);

            $user = User::create($validateDataUser);

            return response()->json([
                'message' => 'User Created Successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validateDataUser = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'cpf' => [
                    'required',
                    'string',
                    'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/',
                    function ($att, $value, $fail) use ($id) {
                        $userCpfEqual = User::where('cpf', $value)->where('id', '!=', $id)->exists();

                        if ($userCpfEqual) {
                            $fail('O CPF informado ja esta em uso.');
                        }
                    }
                ],
                'birth_date' => [
                    'required',
                    'date',
                    'before:today',
                ],
                'phone' => 'string|min:11',
            ], [
                'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00.',
                'birth_date.before' => 'A data de nascimento deve ser uma data no passado.',
            ]);

            $user = User::findOrFail($id);
            $user->update($validateDataUser);

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User Deleted Successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
