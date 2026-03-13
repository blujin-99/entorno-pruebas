<?php

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;

class UserCrudCest
{
    private function asJson(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
    }

    // ──────────────────────────────────────────────
    // GET /api/users  (lista vacía)
    // ──────────────────────────────────────────────
    public function listaUsuariosVacia(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendGet('/api/users');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseEquals('[]');
    }

    // ──────────────────────────────────────────────
    // POST /api/users  (crear usuario)
    // ──────────────────────────────────────────────
    public function crearUsuario(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([
            'nombre'     => 'Juan',
            'apellido'   => 'Pérez',
            'correo'     => 'juan@test.com',
            'contrasena' => 'secreto123',
        ]));

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'nombre'   => 'Juan',
            'apellido' => 'Pérez',
            'correo'   => 'juan@test.com',
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.id');
    }

    // ──────────────────────────────────────────────
    // POST con datos inválidos
    // ──────────────────────────────────────────────
    public function crearUsuarioSinDatosRequeridos(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([]));

        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['errors' => []]);
    }

    public function crearUsuarioConCorreoInvalido(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([
            'nombre'     => 'Juan',
            'apellido'   => 'Pérez',
            'correo'     => 'no-es-un-correo',
            'contrasena' => 'secreto123',
        ]));

        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.errors.correo');
    }

    // ──────────────────────────────────────────────
    // GET /api/users/{id}
    // ──────────────────────────────────────────────
    public function obtenerUsuarioPorId(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([
            'nombre'     => 'Ana',
            'apellido'   => 'García',
            'correo'     => 'ana@test.com',
            'contrasena' => 'clave456',
        ]));
        $id = $I->grabDataFromResponseByJsonPath('$.id')[0];

        $I->sendGet('/api/users/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id'       => $id,
            'nombre'   => 'Ana',
            'apellido' => 'García',
            'correo'   => 'ana@test.com',
        ]);
    }

    public function obtenerUsuarioInexistente(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendGet('/api/users/9999');
        $I->seeResponseCodeIs(404);
    }

    // ──────────────────────────────────────────────
    // PUT /api/users/{id}
    // ──────────────────────────────────────────────
    public function actualizarUsuario(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([
            'nombre'     => 'Carlos',
            'apellido'   => 'López',
            'correo'     => 'carlos@test.com',
            'contrasena' => 'pass789',
        ]));
        $id = $I->grabDataFromResponseByJsonPath('$.id')[0];

        $this->asJson($I);
        $I->sendPut('/api/users/' . $id, json_encode([
            'nombre'   => 'Carlos Actualizado',
            'apellido' => 'López Nuevo',
        ]));

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'nombre'   => 'Carlos Actualizado',
            'apellido' => 'López Nuevo',
            'correo'   => 'carlos@test.com',
        ]);
    }

    public function actualizarUsuarioInexistente(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPut('/api/users/9999', json_encode(['nombre' => 'X']));
        $I->seeResponseCodeIs(404);
    }

    // ──────────────────────────────────────────────
    // DELETE /api/users/{id}
    // ──────────────────────────────────────────────
    public function eliminarUsuario(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendPost('/api/users', json_encode([
            'nombre'     => 'Luis',
            'apellido'   => 'Martínez',
            'correo'     => 'luis@test.com',
            'contrasena' => 'borrar123',
        ]));
        $id = $I->grabDataFromResponseByJsonPath('$.id')[0];

        $I->sendDelete('/api/users/' . $id);
        $I->seeResponseCodeIs(204);

        $I->sendGet('/api/users/' . $id);
        $I->seeResponseCodeIs(404);
    }

    public function eliminarUsuarioInexistente(FunctionalTester $I): void
    {
        $this->asJson($I);
        $I->sendDelete('/api/users/9999');
        $I->seeResponseCodeIs(404);
    }
}
