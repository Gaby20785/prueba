document.addEventListener('DOMContentLoaded', function() {
    const books = [
        { 
            title: "El cultivo del pepino dulce", 
            author: "Constanza Jana Ayala", 
            img: "css/imagen/libro1.jpg", 
            link: "https://bibliotecadigital.fia.cl/items/dae93c0c-53f0-4dd5-a742-769ab5ec5c8b" 
        },
        { 
            title: "Boletín de vitivinicultura para la zona de mesoclima de la Patagonia occidental de la Región de Aysén", 
            author: "Diego Arribillaga García y Marisol Reyes Muñoz", 
            img: "css/imagen/libro2.jpg", 
            link: "https://bibliotecadigital.fia.cl/items/85e9465b-8314-43cf-92ea-30cc1ca2df2d" 
        },
        { 
            title: "Manual del maestro quesero", 
            author: "Haroldo Magariños Hawkinsr", 
            img: "css/imagen/libro3.jpg", 
            link: "https://bibliotecadigital.fia.cl/items/6d339780-c808-4ccc-975a-102db9ef667c" 
        },
        { 
            title: "Recetario Charcutería Cerdo Avellanero de Lumaco", 
            author: "Francisco Klimscha Bittig", 
            img: "css/imagen/libro4.jpg", 
            link: "https://bibliotecadigital.fia.cl/items/d8d67305-c76a-41c8-9bd5-505fd05c4069" 
        },
        { 
            title: "Estrategia de Innovación y Desarrollo Agrícola para la Región de Tarapacá", 
            author: "Alvaro Carevic Rivera y Juan Scopinich", 
            img: "css/imagen/libro5.jpg", 
            link: "https://bibliotecadigital.fia.cl/items/23689635-aac3-4dca-9d55-7789c254be21" 
        }
    ];

    // Función para cargar la lista de libros con imágenes clickeables
    function loadBooks(filteredBooks) {
        const bookList = document.getElementById('book-list');
        bookList.innerHTML = ''; // Limpiar la lista antes de agregar los libros filtrados

        filteredBooks.forEach(book => {
            const li = document.createElement('li');
            li.innerHTML = `
                <a href="${book.link}" target="_blank">
                    <img src="${book.img}" alt="${book.title}" class="book-image">
                </a>
                <div>
                    <span class="book-title">${book.title}</span>
                    <br>
                    <span class="book-author">${book.author}</span>
                </div>
            `;
            bookList.appendChild(li);
        });

        // Escuchar el clic en el enlace para registrar el boletín visitado
        document.querySelectorAll('#book-list a').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();  // Evita que se abra inmediatamente el enlace

                const boletinTitle = this.parentElement.querySelector('.book-title').textContent; // Obtiene el título del boletín
                const boletinUrl = this.href;  // Obtiene la URL del boletín

                // Recuperar el historial actual desde localStorage
                let historial = JSON.parse(localStorage.getItem('boletines_historial')) || [];

                // Agregar el nuevo boletín al historial
                historial.push({ title: boletinTitle, url: boletinUrl, visitedAt: new Date().toISOString() });

                // Limitar a un número máximo de boletines en el historial
                if (historial.length > 5) {
                    historial.shift();  // Elimina el boletín más antiguo si hay más de 5
                }

                // Guardar el historial actualizado en localStorage
                localStorage.setItem('boletines_historial', JSON.stringify(historial));

                // Redirigir al boletín
                window.location.href = boletinUrl;
            });
        });
    }

    // Mostrar todos los libros inicialmente
    loadBooks(books);

    // Función para filtrar libros
    function searchBooks(event) {
        const searchTerm = event.target.value.toLowerCase();
        const filteredBooks = books.filter(book => {
            const titleMatch = book.title.toLowerCase().includes(searchTerm);
            const authorMatch = book.author.toLowerCase().includes(searchTerm);
            return titleMatch || authorMatch;
        });

        loadBooks(filteredBooks);
    }

    // Agregar listener al campo de búsqueda
    document.getElementById('search-input').addEventListener('input', searchBooks);

    // Mostrar el modal de inicio de sesión
    document.getElementById('login-btn').addEventListener('click', function() {
        document.getElementById('login-modal').style.display = 'block';
    });

    //Botón cierre
    document.getElementById('login-btn').addEventListener('click', function() {
        document.getElementById('login-modal').style.display = 'block';
    });
    
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('login-modal').style.display = 'none';
    });

    // Función para cargar libros desde la base de datos
    function loadBooksFromDB() {
        fetch('cargarBoletines.php')
            .then(response => response.json())
            .then(data => {
                console.log("Libros cargados desde la base de datos:", data);
                // Agregar los libros de la base de datos al inicio del array de libros existente
                books.unshift(...data);
                // Mostrar los libros actualizados
                loadBooks(books);
            })
            .catch(error => console.error('Error al cargar los libros desde la base de datos:', error));
    }

    // Cargar libros desde la base de datos cuando se carga la página
    loadBooksFromDB();
});
