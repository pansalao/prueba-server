<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ExternalLoginController extends Controller
{
    /**
     * Maneja el inicio de sesión automático mediante un payload encriptado.
     */
    public function login(Request $request)
    {
        $payload = $request->query('payload');

        if (!$payload) {
            return redirect('/');
        }

        try {
            // 1. Desencriptar el paquete usando la llave exclusiva de SOGAC
            $sogacKey = env('SOGAC_ACCESS_KEY');
            $cleanSogacKey = base64_decode(str_replace('base64:', '', $sogacKey));
            $encrypter = new \Illuminate\Encryption\Encrypter($cleanSogacKey, config('app.cipher'));
            $decrypted = $encrypter->decryptString($payload);
            $data = json_decode($decrypted, true);

            // Validar que el JSON tenga la estructura esperada
            if (!isset($data['cedula'], $data['fecha_creacion'], $data['firma_validacion'])) {
                return redirect('/');
            }

            // 2. Validar Expiración (5 minutos = 300 segundos)
            $tiempoLimite = 300;
            if ((time() - $data['fecha_creacion']) > $tiempoLimite) {
                return redirect('/');
            }

            // 3. Validar Firma de Seguridad (Re-calculamos en el servidor)
            $cleanSogacKeyStr = str_replace('base64:', '', env('SOGAC_ACCESS_KEY'));

            // La semilla debe ser exacta a la de Python: cedula + timestamp + key
            $seed = $data['cedula'] . $data['fecha_creacion'] . $cleanSogacKeyStr;
            $firmaServidor = hash('sha256', $seed);

            if ($firmaServidor !== $data['firma_validacion']) {
                return redirect('/');
            }

            // 4. Buscar al usuario en la base de datos externa (vía modelo User mapeado)
            $user = User::where('usu_cedula', $data['cedula'])->first();

            if (!$user) {
                return redirect('/');
            }

            // 5. Verificar estatus del usuario (el modelo traduce 'A' a 1)
            if ($user->estatus != 1) {
                return redirect('/');
            }

            // 6. Iniciar Sesión
            Auth::login($user);


            // Redirigir al inicio del sistema
            return redirect()->intended(route('dashboard', absolute: false));

        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect('/');
        } catch (\Exception $e) {
            Log::error("Error en ExternalLogin: " . $e->getMessage());
            return redirect('/');
        }
    }
}
