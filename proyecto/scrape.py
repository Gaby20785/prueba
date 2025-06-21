import sys
import requests
from bs4 import BeautifulSoup
import mysql.connector

# Leer las fuentes desde el archivo temporal
fuentes_file = sys.argv[1]
with open(fuentes_file, 'r') as file:
    fuentes = file.read().split(', ')

# Conectar a la base de datos MySQL
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='boletines'
)

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
}

cursor = conn.cursor()

#Para el sitio de noticias
def scrape_noticias(fuente):
    
    # Hacer la solicitud a la página principal
    response = requests.get(fuente, headers=headers)
    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # Encontrar todos los enlaces de artículos
        enlaces = soup.find_all('a', class_='elementor-post__thumbnail__link')
        
        for enlace in enlaces:
            articulo_url = enlace['href']  # Obtener el enlace del artículo
            scrape_and_store(articulo_url)  # Llamar a la función para procesar cada artículo

# Función para realizar el web scraping y almacenar los articulos(noticias)
def scrape_and_store(articulo_url):

    response = requests.get(articulo_url, headers=headers)
    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # Extraer el título
        titulo = soup.find('h1', class_='elementor-heading-title').text.strip()
        
        # Extraer el contenido del artículo
        contenido_parrafos = [p.text for p in soup.find_all('p')]
        contenido_encabezados = [h3.text for h3 in soup.find_all('h3')]
        
        # Unir todos los párrafos y encabezados en un solo string
        contenido = ' '.join(contenido_parrafos + contenido_encabezados)

        # Texto a eliminar
        texto_a_eliminar = "We use cookies on this website to ensure it works as intended and also with your consent to analyse traffic and help us understand how the site is used."
        text2 ="More information in our cookie policy and our privacy policy."
        text3 = 'I agree'
        text4 = 'X'
        text5 = '© 2024 The Fresh Produce Consortium.'

        # se remueve el texto (no me dejaba eliminar todo junto por alguna razón)
        contenido = contenido.replace(texto_a_eliminar, '')
        contenido = contenido.replace(text2, '')
        contenido = contenido.replace(text3, '')
        contenido = contenido.replace(text4, '')
        contenido = contenido.replace(text5, '')
        contenido = contenido.replace('\n', '')
        contenido = contenido.replace('\n', '')
        contenido = contenido.replace('\n', '')
        contenido = contenido.replace('\n', '')
        
                                  
        # Insertar los datos en la base de datos
        cursor.execute('INSERT INTO noticias (fuente, titulo, contenido) VALUES (%s, %s, %s)', 
                       (articulo_url, titulo, contenido))
        conn.commit()

#para el sitio de productos
def scrape_productos(fuente):

    # Hacer la solicitud a la página principal
    response = requests.get(fuente, headers=headers)
    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        
        productos = soup.find_all('section', class_='entry')
        
        for producto in productos:

            #Nombre
            nombre_tag = producto.find('h3')
            if nombre_tag:
                nombre_a_tag = nombre_tag.find('a')
                nombre = nombre_a_tag.text.strip() if nombre_a_tag else 'N/A'
            else:
                nombre = 'N/A'
            
            # Descripción
            descripcion_tag = producto.find('p')
            descripcion = descripcion_tag.text.strip() if descripcion_tag else 'N/A'
            
            # Fuente
            url_tag = producto.find_all('p')[1].find('a') if len(producto.find_all('p')) > 1 else None
            url = 'https://www.agritechtomorrow.com' + url_tag['href'] if url_tag else 'N/A'

            #Si falta alguno de los datos es porque no es un producto, asi que se ignora
            if nombre != 'N/A' and descripcion != 'N/A' and fuente != 'N/A':
                cursor.execute('INSERT INTO productos (nombre, descripcion, fuente) VALUES (%s, %s, %s)', 
                        (nombre, descripcion, url))
                conn.commit()


# Realizar el web scraping dependiendo del tipo de fuente(dos fuentes funcionales)
for fuente in fuentes:
    if fuente == 'https://www.agritechfuture.com/':
        scrape_noticias(fuente)
    elif fuente == 'https://www.agritechtomorrow.com/products.php':
        scrape_productos(fuente)

# Cerrar la conexión a la base de datos
cursor.close()
conn.close()
