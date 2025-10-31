
Frontend del proyecto. Hecho con **Symfony + Twig** para mostrar de forma simple los resultados del ETL, con autenticación vía **Google OAuth 2.0 (GIS)**.  
El objetivo es tener un panel limpio que lea los datos del backend y que solo se acceda después de iniciar sesión con Google.

---

## Qué hace

- **Autenticación:** `/login` muestra el botón de Google. El ID token se valida en `/auth/callback` del servidor.  
  Si todo es correcto, se crea una sesión segura.  
  También existe `/logout` (POST) para cerrar sesión.
- **Dashboard (`/`) protegido:**
  - Selector de ejecución (cada corrida del ETL genera una nueva).
  - Gráfica de **género** (tipo pie).
  - Gráfica de **edades** (tipo bar).
  - Tabla con categorías (título de empresa del dataset → “OS”).
- Si no hay sesión, se redirige automáticamente a `/login`.

---

## Requisitos

- PHP 8.x (probado con `C:\xampp\php\php.exe`)
- Composer
- Base de datos ya generada por el backend (`etl_backend`)
- Un **Client ID de Google** (OAuth Web) con **origin**:  
  `http://127.0.0.1:8001`

---

```env
APP_ENV=prod
TIMEZONE=America/El_Salvador

# Base de datos (la misma del backend)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=etl_backend
DB_USER=root
DB_PASSWORD=

# Necesario para el router
DEFAULT_URI=http://127.0.0.1:8001

# Google OAuth 2.0
GOOGLE_CLIENT_ID=xxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com

    En Google Cloud Console → Credentials → OAuth Client ID (Web)
    Agregar en Authorized JavaScript origins:
    http://127.0.0.1:8001

