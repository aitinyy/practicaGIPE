<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
    * @OA\Get(
    *     tags={"Usuarios"},
    *     path="/api/usuarios",
    *     summary="Mostrar usuarios",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los deportes."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function index()
    {
        $usuarios = User::all();
        return $usuarios;
    }

    /**
     * Crear usuario
     * @OA\Post (
     *     path="/api/usuarios",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"",
     *                     "email":"",
     *                     "password":""
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="email", type="string", example=""),
     *              @OA\Property(property="password", type="string", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    

    public function store(Request $request)
    {
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);

        if(empty($user->name) || empty($user->email) || empty($user->password)){
            $user = "Usuario no completo";
        }else{
            $user->save();
        }

        
        return $user;
        
    }


     /**
     * Update Users
     * @OA\Put (
     *     path="/api/usuarios/{id}",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="password"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"",
     *                     "email":"",
     *                     "password":""
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example=""),
     *              @OA\Property(property="email", type="string", example=""),
     *              @OA\Property(property="password", type="password", example="")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($request->id);

        if(!empty($request->name)){
            $user->name=$request->name;
        }

        if(!empty($request->email)){
            $user->email=$request->email;
        }

        if(!empty($request->password)){
            $user->password=Hash::make($request->password);
        }
        
        $user->save();

        return $user;
    }

    /**
     * Delete User
     * @OA\Delete (
     *     path="/api/usuarios/{id}",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="deleted succesfully")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $user = User::destroy($request->id);

        return $user;
    }

}
