# Klientu un pasūtījumu pārvaldības sistēma

Laravel aplikācija klientu un pasūtījumu pārvaldībai ar lomu balstītu piekļuves kontroli, REST API un Blade + Alpine.js priekšgalu.

## Prasības

- [**PHP**](https://www.php.net/downloads) >= 8.2
- [**Composer**](https://getcomposer.org/download/) >= 2.x
- [**Node.js**](https://nodejs.org/) >= 18.x un **npm**
- [**Docker**](https://www.docker.com/products/docker-desktop/) un **Docker Compose** (ieteicams)

## Tehnoloģijas

- Laravel 12
- MySQL 8.0
- Laravel Breeze + Sanctum
- Blade, Alpine.js, Tailwind CSS
- Vite
- Docker Compose

## Uzstādīšana

### 1. Klonēt repozitoriju

```bash
git clone https://github.com/rendijsb/test-project.git
cd test-project
```

### 2. Instalēt atkarības

```bash
composer install
npm install
```

### 3. Vides konfigurācija

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Palaist ar Docker

```bash
docker compose up -d
```

Tiek palaisti divi servisi:
- **app** — PHP 8.4 + Apache uz `http://localhost:8000`
- **mysql** — MySQL 8.0 uz porta `3306`

### 5. Palaist migrācijas un ievietot testa datus

```bash
docker compose exec app php artisan migrate --seed
```

### 6. Kompilēt priekšgala resursus

```bash
npm run dev
```

Produkcijai:

```bash
npm run build
```

## Noklusējuma administratora konts

| Lauks | Vērtība |
|-------|---------|
| E-pasts | `test@mail.com` |
| Parole | `password` |

Sēdētājs izveido arī 50 testa klientus un 30 testa pasūtījumus.

## REST API

Visi API maršruti pieprasa autentifikāciju ar Laravel Sanctum.

### Klienti

| Metode | Galapunkts | Apraksts |
|--------|------------|----------|
| GET | `/api/customers` | Visu klientu saraksts |
| POST | `/api/customers` | Izveidot klientu |
| GET | `/api/customers/{id}` | Iegūt klientu |
| PUT | `/api/customers/{id}` | Atjaunināt klientu |
| DELETE | `/api/customers/{id}` | Dzēst klientu |
| GET | `/api/customers/{id}/orders` | Iegūt klienta pasūtījumus |

### Pasūtījumi

| Metode | Galapunkts | Apraksts |
|--------|------------|----------|
| GET | `/api/orders` | Visu pasūtījumu saraksts |
| POST | `/api/orders` | Izveidot pasūtījumu |
| GET | `/api/orders/{id}` | Iegūt pasūtījumu |
| PUT | `/api/orders/{id}` | Atjaunināt pasūtījumu |
| DELETE | `/api/orders/{id}` | Dzēst pasūtījumu |

**Pasūtījumu filtri:** `?status=pending`, `?customerId=1`

### Lietotāji (tikai administrators)

| Metode | Galapunkts | Apraksts |
|--------|------------|----------|
| GET | `/api/users` | Visu lietotāju saraksts |
| POST | `/api/users` | Izveidot lietotāju |
| GET | `/api/users/{id}` | Iegūt lietotāju |
| PUT | `/api/users/{id}` | Atjaunināt lietotāju |
| DELETE | `/api/users/{id}` | Dzēst lietotāju |

**Lietotāju meklēšana:** `?search=admin`

## Projekta struktūra

```
app/
├── Enums/              # OrderStatusEnum, UserRoleEnum
├── Http/
│   ├── Controllers/
│   │   ├── Api/        # REST API kontrolleri
│   │   └── Web/        # Blade skatu kontrolleri
│   ├── Requests/       # Form Request validācija
│   └── Resources/      # JSON API resursi
├── Models/             # Eloquent modeļi ar relācijām un query scopes
├── Policies/           # Autorizācijas politikas
└── Services/
    └── Repositories/   # Datu piekļuves slānis (Repository pattern)
```
