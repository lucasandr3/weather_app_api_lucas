<?php

namespace App\Http\Controllers\Autenticacao;

use App\DTO\Autenticacao\RegistroDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Autenticacao\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AutenticacaoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/registro",
     *     summary="Registro de um novo usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", format="name", example="username"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cadastro realizado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsIn...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Requisição não pode ser processada."
     *      )
     * )
     */
    public function registro(RegisterRequest $request): JsonResponse
    {
        $usuarioDto = RegistroDTO::fromArray($request->validated());
        User::query()->create($usuarioDto->toArray());

        return response()->json(['data' => [], 'message' => 'Registro realizado com sucesso'], 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Autenticação de usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsIn...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $credenciais = $request->only('email', 'password');

        if (!Auth::attempt($credenciais)) {
            return response()->json(['message' => 'Dados Inválidos.'], 401);
        }

        $usuario = Auth::user();
        $token = $usuario?->createToken('access_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => new UserResource($usuario)]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Encerra a sessão do usuário",
     *     tags={"Autenticação"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=204,
     *         description=""
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([], 204);
    }
}
