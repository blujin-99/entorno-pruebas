Feature: Registro de usuarios
  Como cliente de la API
  Quiero registrar un usuario nuevo
  Para permitirle autenticarse en el sistema

  Scenario: Crear un usuario exitosamente
    Given que preparo una petición POST a "/api/usuarios"
    And el cuerpo JSON contiene:
      """
      {
        "correo": "Lgimenez@example.com",
        "contrasena": "123456",
        "nombre": "Laura",
        "apellido":"Gimenez"
      }
      """
    When ejecuto la petición
    Then la respuesta debe tener el código 201
    And el JSON debe contener el campo "id"
    And el JSON debe contener "email" con valor "test@example.com"