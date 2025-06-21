import unittest
import requests

#modificar de ser necesario
project_dir = 'proyecto2025-hito4'
BASE_URL = f'http://localhost/{project_dir}/proyecto'

class TestLogin(unittest.TestCase):

    @classmethod
    def setUpClass(cls):
        cls.url_login = f'{BASE_URL}/login.php'

    def test_login_valid_credentials(self):
        data = {'email': 'admin@admin.cl', 'password': 'adminpass'}
        response = requests.post(self.url_login, json=data)
        self.assertEqual(response.status_code, 200)
        json_response = response.json()
        self.assertNotIn('error', json_response)
        self.assertIn('email', json_response)
        self.assertIn('role', json_response)

    def test_login_wrong_password(self):
        data = {'email': 'admin@admin.cl', 'password': 'igiugiu'}
        response = requests.post(self.url_login, json=data)
        self.assertEqual(response.status_code, 401)
        json_response = response.json()
        self.assertIn('error', json_response)
        self.assertEqual(json_response['error'], 'Correo o contraseña incorrectos')

    def test_login_nonexistent_user(self):
        data = {'email': 'usuario@noexistente.com', 'password': 'holaaa'}
        response = requests.post(self.url_login, json=data)
        self.assertEqual(response.status_code, 401)
        json_response = response.json()
        self.assertIn('error', json_response)
        self.assertEqual(json_response['error'], 'Correo o contraseña incorrectos')

class TestUserCreation(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.url_create_user = f'{BASE_URL}/crear_usuario.php'
        cls.url_delete_user = f'{BASE_URL}/eliminar_usuario.php'
        cls.test_email = 'testuser@example.com'
    
    def test_create_user_success(self):
        data = {
            'email': self.test_email,
            'password': 'testpass123',
            'role': 'user'
        }
        response = requests.post(self.url_create_user, data=data, allow_redirects=False)
        self.assertEqual(response.status_code, 302)
        self.assertIn('Location', response.headers)
        self.assertTrue(response.headers['Location'].endswith('administrador.php'))

    def test_create_user_already_exists(self):
        data = {
            'email': 'admin@admin.cl',
            'password': 'holaaa',
            'role': 'admin'
        }
        response = requests.post(self.url_create_user, data=data)
        self.assertNotEqual(response.status_code, 302)
        self.assertIn('Error', response.text)

    @classmethod
    def tearDownClass(cls):
        params = {'email': cls.test_email}
        response = requests.get(cls.url_delete_user, params=params)
        if response.status_code != 200:
            print(f'Advertencia: No se pudo eliminar el usuario {cls.test_email} después de las pruebas.')

if __name__ == '__main__':
    unittest.main()
