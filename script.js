document.getElementById("loadDataBtn").addEventListener("click", function() {
    // Crear una nueva solicitud XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "data.json", true);
    
    xhr.onload = function() {
        if (this.status === 200) {
            const data = JSON.parse(this.responseText);
            let output = "<ul>";
            
            data.forEach(item => {
                output += `
                    <li>
                        <h2>${item.name}</h2>
                        <p><strong>Precio:</strong> ${item.price}</p>
                        <p>${item.description}</p>
                        <pre><code>${JSON.stringify(item, null, 2)}</code></pre>
                    </li>
                `;
            });
            
            output += "</ul>";
            document.getElementById("dataContainer").innerHTML = output;
        }
    };
    
    xhr.onerror = function() {
        document.getElementById("dataContainer").innerHTML = "Hubo un error al cargar los datos.";
    };
    
    xhr.send();
});
