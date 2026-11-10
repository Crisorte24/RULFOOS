function startVoiceNavigation() {
    // Comprobamos si el navegador soporta la API de reconocimiento de voz
    if (!('webkitSpeechRecognition' in window)) {
        alert("Lo siento, tu navegador no soporta la navegación por voz.");
        return;
    }

    // Creamos una instancia del reconocimiento de voz
    const recognition = new webkitSpeechRecognition();
    recognition.lang = "es-ES";
    recognition.continuous = false;
    recognition.interimResults = false;

    recognition.onstart = function() {
        console.log("Escuchando...");
    };

    recognition.onresult = function(event) {
        // Obtenemos el resultado del reconocimiento de voz
        const speechResult = event.results[0][0].transcript.toLowerCase();
        console.log("Reconocido:", speechResult);

        // Acciones basadas en el resultado
        if (speechResult.includes("inicio")) {
            const messages = [
                "Vamos a la página de inicio.",
                "Llevándote a la página principal."
            ];
            speak(getRandomMessage(messages));
            window.location.href = "inicio rulfoos.php";
        } else if (speechResult.includes("servicios")) {
            const messages = [
                "Redirigiendo a la página de servicios.",
                "Abriendo la sección de servicios.",
                "Accediendo a la página de servicios."
            ];
            speak(getRandomMessage(messages));
            window.location.href = "Servicios rulfoos php.php";
        } else if (speechResult.includes("productos")) {
            const messages = [
                "Redirigiendo a la página de productos.",
                "Vamos a ver los productos disponibles.",
                "Mostrando la página de productos."
            ];
            speak(getRandomMessage(messages));
            window.location.href = "carrito rulfoos php.php";
        } else if (speechResult.includes("contacto")) {
            const messages = [
                "Redirigiendo a la página de contacto.",
                "Vamos a la sección de contacto.",
                "Abriendo la página para ponerte en contacto con nosotros."
            ];
            speak(getRandomMessage(messages));
            window.location.href = "contacto rulfos php.php";
        } else if (speechResult.includes("iniciarsesion") || speechResult.includes("login")) {
            const messages = [
                "Redirigiendo a la página de iniciar sesion.",
                "Llevándote a la página de login.",
                "Accediendo a la sección de inicio de sesion."
            ];
            speak(getRandomMessage(messages));
            window.location.href = "login.php";
        } else {
            const errorMessages = [
                
                "No entendí el comando, intenta con 'inicio', 'servicios', 'productos', 'contacto' o 'Login'.",
                "Lo siento, no pude reconocer el comando. Intenta decir 'inicio', 'servicios', 'productos', 'contacto' o 'Login'."
            ];
            speak(getRandomMessage(errorMessages));
        }
    };

    recognition.onerror = function(event) {
        console.error("Error de reconocimiento de voz:", event.error);
    };

    recognition.onend = function() {
        console.log("Reconocimiento de voz finalizado.");
    };

    // Iniciar la escucha de voz
    recognition.start();
}

// Función para hacer que el navegador hable
function speak(text) {
    if ('speechSynthesis' in window) {
        const speech = new SpeechSynthesisUtterance();
        speech.text = text;
        speech.lang = "es-ES";
        speech.rate = 1; // Velocidad de la voz
        speech.pitch = 1; // Tono de la voz
        window.speechSynthesis.speak(speech);
    } else {
        console.error("API de síntesis de voz no soportada por este navegador.");
    }
}

// Función para obtener un mensaje aleatorio de una lista de mensajes
function getRandomMessage(messages) {
    const randomIndex = Math.floor(Math.random() * messages.length);
    return messages[randomIndex];
}
