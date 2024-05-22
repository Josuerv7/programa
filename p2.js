const express = require("express");
const speakeasy = require("speakeasy");
const qrcode = require("qrcode");
const axios = require("axios");
const path = require("path");
const session = require("express-session");

const app = express();
const port = 1700;

const secret = speakeasy.generateSecret({ length: 20 });
const secretBase32 = secret.base32;

const qrCodeUrl = `otpauth://totp/YourAppName?secret=${encodeURIComponent(secretBase32)}`;

function verifyToken(token, secret) {
  return speakeasy.totp.verify({ secret, encoding: "base32", token });
}
// Configuración de sesiones
app.use(session({
    secret: 'tu_secreto_de_sesion', // Cambia esto por un secreto seguro
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false } // Usa 'true' si estás usando HTTPS
}));

// Middleware para parsear datos del cuerpo de las solicitudes
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Middleware para servir archivos estáticos
app.use(express.static(path.join(__dirname, 'public')));

// Middleware para verificar autenticación
function isAuthenticated(req, res, next) {
    if (!req.session) {
      return res.redirect('/');
    }
    if (req.session.isAuthenticated) {
      return next();
    } else {
      res.redirect('/');
    }
  }
  

// Página de inicio de sesión
app.get("/", (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Página de registro
app.get("/register", (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'register.html'));
  
});

//...s

  
  //...
// Ruta para obtener la imagen del código QR
app.get("/qr-code", (req, res) => {
    qrcode.toDataURL(qrCodeUrl, (err, imageUrl) => {
        if (err) {
            console.error("Error generando el código QR:", err);
            res.status(500).send("Error generando el código QR");
        } else {
            res.send(imageUrl);
        }
    });
});

// Proceso de inicio de sesión
app.post("/login", async (req, res) => {
    const { username, password } = req.body;
    try {
      const response = await axios.post("https://mvp6e.000webhostapp.com/login.php", new URLSearchParams({
        usuario: username,
        contrasena: password
      }));
      if (response.data.status === "ok") {
        req.session.isAuthenticated = true; // Establecer la sesión en true
        res.redirect("/scan");
      } else {
        res.send("<br><br><br><br><br><br> <h2 style='text-align: center; color: red;'>Contraseña inválida!</h2>");
      }
    } catch (error) {
      res.send("<br><br><br><br><br><br> <h2 style='text-align: center; color: red;'>Error en la solicitud!</h2>");
    }
  });

// Proceso de registro de usuario
app.post("/register", async (req, res) => {
    const { username, password } = req.body;
    try {
        const response = await axios.post("https://mvp6e.000webhostapp.com/adduser.php", new URLSearchParams({
            usuario: username,
            contrasena: password
        }));
        res.json(response.data);
    } catch (error) {
        res.send("<br><br><br><br><br><br> <h2 style='text-align: center; color: red;'>Error en la solicitud!</h2>");
    }
});


// Ruta para escanear el código QR (accesible solo después de iniciar sesión)
app.get("/scan", isAuthenticated, (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'scan.html'));
  });
  
  // Ruta para verificar el token de Google Authenticator
  app.post("/verify", isAuthenticated, (req, res) => {
    const { token } = req.body;
    const isVerified = verifyToken(token, secretBase32);
    if (isVerified) {
      res.send("<h2 style='text-align: center; color: green;'>¡Autenticación exitosa!</h2>");
    } else {
      res.send("<h2 style='text-align: center; color: red;'>¡Token inválido!</h2>");
    }
});

app.listen(port, () => {
    console.log(`Servidor corriendo en http://localhost:${port}`);
});
